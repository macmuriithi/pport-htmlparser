<?php

namespace pPort\Html;

/**
 * Parses HTML templates into PHP
 * Supports PHP written into HTML.
 *
 * Use-case : Write frontend code logic with PHP as HTML tags
 *
 *
 * @author <Martin Muriithi> <martin@pporting.org>
 *
 */
class Parser
{
    public $template = NULL;
    public $params = [];
    public $data = [];
    public function __construct($template = NULL, $params = [])
    {
        $template = $template ? $template : (isset($_POST['template']) ? $_POST['template'] : NULL);
        $this->template = htmlspecialchars_decode(html_entity_decode($template));
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function setData($data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key)
    {
        isset($this->data[$key]) ? $this->data[$key] : NULL;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function run()
    {
        $template = $this->replaceParams($this->template);

        $template = $this->replaceTags($template);


        $template = $this->eval($template);
        return $template;
    }

    public function replaceParams($template)
    {

        foreach ($this->params as $key => $val) {
            if (is_object($val)) {
                $val = $val($this);
            }
            $template = str_replace("{@" . $key . "}", $val, $template);
        }
        $template = str_replace("@_", '$_', $template);
        return $template;
    }

    public function eval($template)
    {

        ob_start();
        extract($this->params);
        extract($this->data);

        eval("?>" . $template);
$obContents = ob_get_clean();
return $obContents;
}


public function replaceTags($template)
{

$template = str_replace("<@", '<?php ', $template);
        $template = str_replace("<#", '<?php ', $template);
        $template = str_replace("</@", '<?php ', $template);
        $template = str_replace("<!--?", '<?', $template);

        $template = str_replace(":/>", ":?>", $template);
        $template = str_replace("/>", ";?>", $template);

        $template = str_replace("?-->", "?>", $template);
        $template = str_replace(":>", ":?>", $template);
        $template = str_replace(":>", ":?>", $template);

        return $template;
    }
};
<?php

namespace pPort\HtmlParser;

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
    public function __construct($template = NULL, $params = [])
    {
        $template = $template ? $template : $_POST['template'];
        $this->template = htmlspecialchars_decode($template);
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
        eval("?>" . $template);
$obContents = ob_get_clean();
return $obContents;
}


public function replaceTags($template)
{
$template = str_replace("<@", '<?php ', $template);
        $template = str_replace(":/>", ":?>", $template);
        $template = str_replace("/>", ";?>", $template);

        return $template;
    }
};
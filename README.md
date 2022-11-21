# README

Pport HTMLParse Package : Enables writing of front-end interfaces with PHP as HTML tags.

# Installation

Install pport\htmlparser using composer :

    composer require pport/htmlparser

## To Create FrontEnd Applications Supported

#### 1. Create a Front-End HTML. Ensure you include pport-reactive.js . Your HTML templates need to be placed in <fetch> tag

```html
<!DOCTYPE html>
<html lang="en">
  <head> </head>

  <body>
    <fetch route="http://localhost:8000/" params='{"id":"1"}'>
      <@echo('Test') /> <@if(isset(@_GET['form_error'])):/> <@var_dump('Test')
      /> <@endif/>
    </fetch>

    <script type="text/javascript" src="/scripts/jquery.js"></script>
    <script type="text/javascript" src="/scripts/pport-reactive.js"></script>
    <script type="text/javascript">
      Reactive.start();
    </script>
  </body>
</html>
```

#### 2. On your server implement your logic to receive and execute the requests into pure HTML templates

```php
<?php
header("Access-Control-Allow-Origin: *");
include "vendor/autoload.php";
//Test HTML template
//$template = '<@echo("Test") /><@if(isset($_GET["form_error"])):/> <@var_dump("Test") /> <@endif/> ';
$template = $_POST['template'];
$parser = new Parser($template);
$template = $parser->run();
exit($template);
;?>
```

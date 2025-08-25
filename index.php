<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT");
session_start();
define( 'MAX_SESSION_TIEMPO', 3600 * 2500 );


global $Dir_;
global $Data_;
$Dir_ = dirname($_SERVER["PHP_SELF"]) .'/';
$Data_ = str_replace('/','',$Dir_);


/*$Dir_ = '/Mozo-Local/';
$Data_ = 'MozoApp';*/


require_once './vendor/autoload.php';  
spl_autoload_register(function ($class_name) {
    if(file_exists("./Class/".$class_name.".php")){
        require_once("./Class/".$class_name.".php");
    }
});

require_once("./Router.php");

$App = new App();
$App->run();
?>
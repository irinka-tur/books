<?php
require 'config.php';

spl_autoload_register(function ($class) {
    $file = str_replace("\\", "/", $class) . '.php';
    if (is_readable($file)) {
        return include $file;
    }
    return false;
});

$controller = new Controller\MainController(DEFAULTPAGE, BASEURL);
$controller->run();


<?php
spl_autoload_register(function ($class) {


    if (file_exists($class . '.php')) {
        include $class . '.php';
    } else
        throw new Exception('Class ' . $class . ' not loaded');
}, true);
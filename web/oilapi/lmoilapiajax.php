<?php
/**
 * Created by PhpStorm.
 * User: User
* Date: 11.12.2017
* Time: 18:38
*/

ini_get('date.timezone') or ini_set('date.timezone', 'Europe/Moscow');
ini_set('display_errors', 0);
error_reporting(E_ALL);

include_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';

//echo md5('http://www.moly-shop.ru');
//var_dump($_SERVER);

new LmApp();
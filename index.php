<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 05.10.16
 * Time: 15:51
 */

header('Content-type: text/html; charset=utf-8');
ini_set("display_errors", "1");
error_reporting(E_ALL);

/**
 * class autoload method
 * @param $class_name
 */

function __autoload($class_name){
    include $class_name.'.class.php';
}

$action = Helper::getArrayElement($_REQUEST, 'ACTION', '');
$component = new Component();
$component->executeComponent($action);

/**
 * example for creating multi DB instances
 * $db = \DBMain::getInstance();
 * a second singleton db connection to an another DB
 * $db2 = \DBSecond::getInstance();
 */
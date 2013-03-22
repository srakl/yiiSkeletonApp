<?php

$dirname = dirname(__FILE__);
$hostname = $_SERVER['SERVER_NAME'];

// change the following paths if necessary
$yii = $dirname . '/yii/yii.php';
$config = $dirname . '/protected/config/main.php';
$shortcuts = $dirname . '/protected/helpers/shortcuts.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
require_once($shortcuts);
Yii::createWebApplication($config)->run();

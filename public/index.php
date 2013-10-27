<?php

$dirname = dirname(__FILE__);
$hostname = $_SERVER['SERVER_NAME'];
$shortcuts = $dirname . '/../protected/helpers/shortcuts.php';

if ($hostname == 'travisstroud.co.uk') {
    $config = $dirname . '/../protected/config/production.php';
    $yii = $dirname . '/../vendor/yiisoft/yii/framework/yiilite.php';
} elseif($hostname == 'travisstroud.dev' || $hostname == 'localhost') {
    $yii = $dirname . '/../vendor/yiisoft/yii/framework/yii.php';
    $config = $dirname . '/../protected/config/local.php';
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
} else {
    $yii = $dirname . '/../vendor/yiisoft/yii/framework/yii.php';
    $config = $dirname . '/../protected/config/main.php';
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
}

require_once(dirname(__FILE__).'/../vendor/autoload.php');

require_once($yii);
require_once($shortcuts);
Yii::createWebApplication($config)->run();
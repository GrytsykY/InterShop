<?php
define('WG_ACCESS',true);

header('Content-Type:text/html;charset=utf-8');
session_start();

//error_reporting(0); // отчет об ошибках отключен

require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';
require_once 'libraries/function.php';

use core\base\exceptions\DbException;
use \core\base\exceptions\RouteException;
use \core\base\controllers\RouteController;

//$s = \core\base\settings\Settings::instance();
//$s1 = \core\base\settings\ShopSettings::instance();
//
//exit;

try{
    RouteController::instance()->route();
}
catch (RouteException $e){
    exit($e->getMessage());
}
catch (DbException $e){
    exit($e->getMessage());
}
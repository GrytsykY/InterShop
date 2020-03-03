<?php
define('WG_ACCESS',true);

header('Content-Type:text/html;charset=utf-8');
session_start();

require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';
require_once 'libraries/function.php';

use \core\base\exceptions\RouteException;
use \core\base\controllers\RouteController;

use core\base\settings\Settings;

$s = Settings::get('routed');
$s1 = Settings::get('teplateArr');


try{
    RouteController::instance()->route();
}
catch (RouteException $e){
    exit($e->getMessage());
}
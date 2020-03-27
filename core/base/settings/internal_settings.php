<?php
defined('WG_ACCESS') or die('Access denied');

const TEMPLATE = 'templates/default/';
const ADMIN_TEMPLATE = 'core/admin/views/';
const UPLOAD_DIR = 'userfiles/';

const COOKIE_VERSION = '1.0.0';
const CRYPT_KEY = 'H+MbQeThVmYq3t6w?D(G+KbPeShVkYp3x!A%D*G-KaPdSgVk4t7w!z%C*F-JaNdRmYq3t6w9z$C&F)J@SgVkYp3s6v9y$B&EaPdSgUkXp2s5v8y/F-JaNdRgUjXn2r5u';
const COOKIE_TIME = 60;
const BLOCK_TIME = 3;

const QTY = 8;
const QTY_LINKS = 3;

const ADMIN_CSS_JS = [
    'styles' => ['css/main.css'],
    'scripts' => [],
];
const USER_CSS_JS = [
    'styles' => ['css/style.css'],
    'scripts' => [],
];

use core\base\exceptions\RouteException;

function autolosdMainClasses($class_name){
    $class_name = str_replace('\\','/',$class_name);


    if (!@include_once $class_name . '.php'){
        throw new RouteException('Не верное имя файла для подключения - '.$class_name);
    }
}
spl_autoload_register('autolosdMainClasses');
<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\base\settings\ShopSettings;


class RouteController
{
    static private $_instance;

    protected $routes;

    protected $controller;
    protected $imputMethod;   // собирает данные из БД
    protected $outputMethod;  // подключение вида
    protected $parameters;

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    static public function getInstance()
    {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }
        return self::$_instance = new self;
    }

    private function __construct()
    {
        // получаем адрессную строку
        $adress_str = $_SERVER['REQUEST_URI'];

        //
        if (strrpos($adress_str, '/') === strlen($adress_str) - 1 && strrpos($adress_str, '/') !== 0) {
            $this->redirect(rtrim($adress_str, '/'), 301);
        }

        //
        $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));

        if ($path === PATH) {
            // запись маршрутов
            $this->routes = Settings::get('routes');

            // проверка
            if (!$this->routes) throw new RouteException('Сайт находится на техническом обслуживании');

            if (strpos($adress_str, $this->routes['admin']['alias']) === strlen(PATH)) {

                // обрезаем строку
                $url = explode('/', substr($adress_str, strlen(PATH . $this->routes['admin']['alias']) + 1));

                if ($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])) {

                    // перемешиваем массив и получаем его с нуля
                    $plugin = array_shift($url);

                    // формируем имя к файлу плагина
                    $pluginSettings = $this->routes['settings']['path'] . ucfirst($plugin . 'Settings');


                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . 'php')) {
                        $pluginSettings = str_replace('/', '\\', $pluginSettings); // меняем слеши "/" на "\"
                        $this->routes = $pluginSettings::get('routes');                        // перезаписываем
                    }

                    // записуем имя дерективы или "/"
                    $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '/' : '/';

                    // проверяем если есть "//" то обрезаем "/"
                    $dir = str_replace('//', '/', $dir);

                    // формируем строку, добавляем дерикторию либо "/"
                    $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;

                    $hrUrl = $this->routes['plugins']['hrUrl'];

                    $route = 'plugins';

                } else {
                    $this->controller = $this->routes['admin']['path'];

                    $hrUrl = $this->routes['admin']['hrUrl'];

                    $route = 'admin';

                }

            } else {
                $url = explode('/', substr($adress_str, strlen(PATH)));
                $hrUrl = $this->routes['user']['hrUrl'];

                $this->controller = $this->routes['user']['path'];

                $route = 'user';
            }

            $this->createRoute($route, $url);

            // создаем набор параметров
            //----------------------
            if ($url[1]) {
                $count = count($url); // подсчитаем колличество елементов массива url
                $key = "";

                if (!$hrUrl) {
                    $i = 1;
                } else {
                    $this->parameters['alias'] = $url[1];
                    $i = 2;
                }

                for (; $i < $count; $i++) {
                    if (!$key) {
                        $key = $url[$i];
                        $this->parameters[$key] = '';
                    }else{
                        $this->parameters[$key] = $url[$i];
                        $key = '';
                    }
                }
            }
            exit();
        } else {
            try {
                throw new \Exception('Не корректная директория сайта');
            } catch (\Exception $e) {
                exit($e->getMessage());
            }
        }

    }

    private function createRoute($var, $arr)
    {
        $route = [];

        if (!empty($arr[0])) {
            if ($this->routes[$var]['routes'][$arr[0]]) {
                $route = explode('/', $this->routes[$var]['routes'][$arr[0]]);

                $this->controller .= ucfirst($route[0] . 'Controller');
            } else {
                $this->controller .= ucfirst($arr[0] . 'Controller');
            }
        } else {
            $this->controller .= $this->routes['default']['controller'];
        }

        $this->imputMethod = $route[1] ? $route[1] : $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2] ? $route[2] : $this->routes['default']['outputMethod'];

        return;
    }


}
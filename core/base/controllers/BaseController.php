<?php


namespace core\base\controllers;


use core\base\exceptions\RouteException;
use core\base\settings\Settings;

abstract class BaseController
{
    use \core\base\controllers\BaseMethod;

    protected $page;
    protected $errors;

    protected $controller;
    protected $imputMethod;   // собирает данные из БД
    protected $outputMethod;  // подключение вида
    protected $parameters;

    public function route(){
        $controller = str_replace('/','\\',$this->controller);

        try{
            $object = new \ReflectionMethod($controller, 'request');

            $args = [
                'parameters' => $this->parameters,
                'inputMethod' => $this->imputMethod,
                'outputMethod' => $this->outputMethod,
            ];

            $object->invoke(new $controller, $args);
        }catch (\ReflectionException $e){
            throw new RouteException($e->getMessage());
        }

    }

    public function request($args){
        $this->parameters = $args['parameters'];

        $inputData = $args['inputMethod'];//debug($inputData);
        $outputData = $args['outputMethod'];

        $data = $this->$inputData();

        if (method_exists($this, $outputData)) {
            $page = $this->$outputData($data);
            if ($page) $this->page = $data;
        }
        elseif ($data) {
            $this->page = $data;
        }

        //$this->page = $this->$outputData();

        if ($this->errors){
            $this->writeLog();
        }

        $this->getPage();
    }

    protected function render($path = '', $parameters = []){
        extract($parameters);

        if (!$path){
            $class = new \ReflectionClass($this);

            $space = str_replace('\\','/',$class->getNamespaceName().'\\');
            $routes = Settings::get('routes');

            if ($space === $routes['user']['path']){
                $template = TEMPLATE;
            }else{
                $template = ADMIN_TEMPLATE;
            }

            $path = $template . explode('controller', strtolower($class->getShortName()))[0];
        }

        // Запись в буфер обмена
        ob_start(); // открываем

        if (!@include_once $path . '.php'){
            throw new RouteException('Отсутсвует шаблон - '.$path);
        }

        return ob_get_clean(); // закрываем
    }

    protected function getPage(){
        if (is_array($this->page)){
            foreach ($this->page as $block) echo $block;
        }else{
            echo $this->page;
        }
        exit();
    }
}
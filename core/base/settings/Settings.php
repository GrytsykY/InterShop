<?php


namespace core\base\settings;


class Settings
{
    static private $_instance;

    private $routes = [
        'admin' => [
            'name' => 'admin',
            'path' => 'core/admin/controllers/',
            'hrUrl' => false,
            'routes' => [

            ],
        ],
        'settings' => [
            'path' => 'core/base/settings/',
        ],
        'plugins' => [
            'path' => 'core/plugins/',
            'hrUrl' => false,
        ],
        'user' => [
            'path' => 'core/user/controllers/',
            'hrUrl' => true,
            'routes' => [

            ],
        ],
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData',
        ],
    ];

    private $teplateArr = [
        'text' => ['phone','adress','name'],
        'textarea' => ['content','keywords'],
    ];

    private $lalala = 'lalala';

    private function __construct()
    {
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    static public function get($property){
        return self::instance()->$property;
    }

    static public function instance(){
        if (self::$_instance instanceof self){
            return self::$_instance;
        }
        return self::$_instance = new self;
    }

    public function clueProperties($class){
        $baseProperties = [];

        foreach ($this as $name => $item){
            $property = $class::get($name);

            if (is_array($property) && is_array($item)){
                $baseProperties[$name] = $this->arrayMergeReqursive($this->$name, $property);
                continue;
            }

            if (!$property) $baseProperties[$name] = $this->$name; // если не "property" записываем  основной объект
        }

        return $baseProperties;
    }

    public function arrayMergeReqursive(){ // склеевание двух массивов

        $arrays = func_get_args();         // получить аргументы

        $base = array_shift($arrays); // вытаскиваем первый елемент масиваб и удаляет

        foreach ($arrays as $array){
            foreach ($array as $key => $value){
                if (is_array($value) && is_array($base[$key])){ // проверяем если равны, тогда вызываем рекурсивно метод
                    $base[$key] = $this->arrayMergeReqursive($base[$key],$value); // слияние рекурсивно двух массивов
                }else{
                    if (is_int($key)){        // если это номерованый массив
                        if (!in_array($value,$base)){  // если не существует
                            array_push($base,$value); // записываем массив
                            continue;                       // выходим
                        }
                    }
                    $base[$key] = $value;  // перезаписываем массив "base" и ячейку "key"
                }
            }
        }

        return $base;  // возращаем массив
    }
}
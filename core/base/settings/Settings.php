<?php


namespace core\base\settings;


use core\base\controllers\Singleton;

class Settings
{
    use Singleton;

    private $routes = [
        'admin' => [
            'alias' => 'admin',
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
            'dir' => false,
        ],
        'user' => [
            'path' => 'core/user/controllers/',
            'hrUrl' => true,
            'routes' => [
                'site' => 'index/inputData',
            ],
        ],
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData',
        ],
    ];
	private $expansion = 'core/admin/expansion/';

    private $defaultTable = 'teachers';

    private $formTemplates = PATH. 'core/admin/views/include/form_templates/';

    private $projecTables = [
    	'teachers' => ['name' => 'Учителя', 'img' => 'pages.png'],
    	'students' => ['name' => 'Ученики'],
	];

    private $templateArr = [
        'text' => ['name'],
        'textarea' => ['content'],
		'radio' => ['visible'],
		'select' => ['menu_position','parent_id'],
		'img' => ['img']
    ];

    private $translate = [
    	'name' => ['Название','Не более 100 символов']
	];

    private $radio = [
    	'visible' => ['Нет','Да','default' => 'Да']
	];

    private  $rootItems = [
    	'name' => 'Корневая',
		'tables' => ['teachers','articles']
	];

    private $blockNeedle = [
    	'vg-rows' => [],
    	'vg-img' => ['img'],
    	'vg-content' => ['content'],
	];

    static public function get($property){
        return self::instance()->$property;
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

        $base = array_shift($arrays); // вытаскиваем первый елемент масива, и удаляет

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
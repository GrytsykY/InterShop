<?php


namespace core\user\controllers;


use core\admin\models\Model;
use core\base\controllers\BaseController;
use core\base\models\Crypt;

class IndexController extends BaseController
{
    protected $name;

    protected function inputData(){

    	$model = Model::instance();

    	$res = $model->get('teachers', [
    		'where' => ['id' => '1,2'],
			'operand' => ['IN'],
			'join' => [
				'stud_teach' => ['on' => ['id', 'teacher']],
				'students' => [
					'fields' => ['name as st_name','content'],
					'on' => ['student','id']
				]
			],
			'join_structure' => true

		]);

    	exit;
    }
}
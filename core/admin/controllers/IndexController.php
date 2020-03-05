<?php


namespace core\admin\controllers;


use core\base\controllers\BaseController;
use core\admin\models\Model;

class IndexController extends BaseController
{
	protected function inputData()
	{
		$db = Model::instance();

		$table = 'teachers';


		$files['gallary_img'] = ['red.jpg','blue.jpg','black.jpg'];
		$files['img'] = 'main_img.jpg';


		$res = $db->add($table, [
			'fields' => ['name'=>'Kolia', 'content'=>'hello'],
			'except' => ['name'],
			'files' => $files,
		]);

		//debug($res);
		exit();
	}
}
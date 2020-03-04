<?php


namespace core\admin\controllers;


use core\base\controllers\BaseController;
use core\base\models\BaseModel;

class IndexController extends BaseController
{
    protected function inputData(){
        $db = BaseModel::instance();

        exit('I am admin pahel');
    }
}
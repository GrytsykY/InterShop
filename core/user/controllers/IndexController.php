<?php


namespace core\user\controllers;


use core\base\controllers\BaseController;
use core\base\models\Crypt;

class IndexController extends BaseController
{
    protected $name;

    protected function inputData(){

    	$str = '1234567890abcdefg';

    	$en_str = Crypt::instance()->encrypt($str);

    	$dec_crypt = Crypt::instance()->decrypt($en_str);

        exit();
    }
}
<?php


namespace core\base\controllers;


class BaseRoute
{
	use Singleton, BaseMethod;

	public static function routeDirection(){

		if (self::instance()->isAjax()){

			exit((new BaseAjax())->route());

		}

		RouteController::instance()->route();
	}

}
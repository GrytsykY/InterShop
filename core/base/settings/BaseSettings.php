<?php


namespace core\base\settings;


use core\base\controllers\Singleton;

trait BaseSettings
{
	use Singleton{
		instance as SingletonInstance;
	}

	private $baseSettings;

	static public function get($property){
		return self::instance()->$property;
	}

	static public function instance(){
		if (self::$_instance instanceof self){
			return self::$_instance;
		}

		self::SingletonInstance()->baseSettings = Settings::instance();
		$baseProperties = self::$_instance->baseSettings->clueProperties(get_class());
		self::$_instance->setProperty($baseProperties);

		return self::$_instance;
	}

	protected function setProperty($properties){ // создаем все свойства
		if ($properties){
			foreach ($properties as $name => $property){
				$this->$name = $property;
			}
		}
	}

}
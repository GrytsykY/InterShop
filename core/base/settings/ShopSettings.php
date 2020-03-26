<?php


namespace core\base\settings;


class ShopSettings
{
	use BaseSettings;

    private $routes = [
        'plugins' => [
            'dir' => 'dir',
            'routes' => [
                'route1' => ['1','2']
            ],
        ],
    ];

	private $templateArr = [
		'text' => ['price','short','name'],
		'textarea' => ['goods_content'],
	];

}
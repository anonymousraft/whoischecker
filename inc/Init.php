<?php
/*
* @package Whoischecker
*/

namespace Inc;

// use Inc\BaseController;

class Init{

    //public $base_controller;

    public static function get_services()
    {
        return [
            Pages\HomePage::class,
            //Base\EnqueueScripts::class
        ];
    }

    public static function register_services()
	{
		foreach (self::get_services() as $class)
		{
            $service = self::instantiate($class);
            
			if (method_exists($service, 'register'))
			{
				$service->register();
			}
		}
	}
    
    private static function instantiate($class)
	{
		$service = new $class();
		return $service;
	}

    public static function indexPage(){
        echo "Autoload Working";
    }
}
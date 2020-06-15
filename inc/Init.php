<?php
/*
* @package Whoischecker
*/

namespace Inc;

class Init{

	public static $page_classes = [];

    public static function get_services()
    {

    	self::$page_classes = [

			'home' => Pages\HomePage::class,
			'bulkwhois' => Pages\BulkWhoisCheck::class,
			'domainwhois' => Pages\DomainWhois::class,
			'upload' => Base\UploadFile::class,
			'export' => Pages\ExportData::class
		];
		
	}
	
	public static function register(string $page_name)
	{
		self::get_services();
		$class = self::$page_classes[$page_name];
		$class_obj = new $class();
		
		if($page_name === 'domainwhois')
		{
			$class_obj->initiate($_POST['domain_name']);
			exit;
		}

		$class_obj->initiate();
	}
}
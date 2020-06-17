<?php
/*
* @package Whoischecker
*/

namespace Inc;

class Init
{

	public static $page_classes = [];

	public static function get_services()
	{

		self::$page_classes = [

			'home' => Pages\HomePage::class,
			'bulkwhois' => Pages\BulkWhoisCheck::class,
			'domainwhois' => Pages\DomainWhois::class,
			'upload' => Base\UploadFile::class,
			'export' => Pages\ExportData::class,
			'dnscheck' => Pages\DnsChecker::class
		];
	}

	public static function register($settings)
	{
		if (isset($settings['page_name'])) {

			self::get_services();
			$class = self::$page_classes[$settings['page_name']];
			$class_obj = new $class();

			if ($settings['page_name'] === 'domainwhois') 
			{
				if(isset($_POST['domain_name']))
				{
					$class_obj->initiate($_POST['domain_name']);
					exit;
				}

				echo 'Please Enter a Valid Domain Name: eg. quatervois.io or https://quatervois.io.<a href="index.php"> <<< Home</a>';
            	exit;
			}

			if ($settings['page_name'] === 'bulkwhois' && !empty($settings['sleep_time'])) 
			{
				$class_obj->initiate($settings['sleep_time']);
				exit;
			}
			elseif($settings['page_name'] === 'bulkwhois')
			{
				$class_obj->initiate();
				exit;
			}

			$class_obj->initiate();
		}
	}
}

<?php
/*
* @package Whoischecker
*/

session_start();

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php'))
{
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

if (class_exists('Inc\\Init'))
{
    $settings = [
        'page_name' => 'bulkwhois',
        //'sleep_time' => 500000 //optional parameter for sleep time. Default is 250000
    ];
    Inc\Init::register($settings);
}
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
        'page_name' => 'upload'
    ];
    Inc\Init::register($settings);
}
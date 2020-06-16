<?php

/*
* @package Whoischecker
*/

if (!file_exists(dirname(__FILE__) . '/vendor/autoload.php'))
{
    require_once dirname(__FILE__).'/views/composerinstall.php';
    exit;
}

require_once dirname(__FILE__) . '/vendor/autoload.php';

if (class_exists('Inc\\Init'))
{
    $settings = [
        'page_name' => 'home'
    ];

    Inc\Init::register($settings);
}
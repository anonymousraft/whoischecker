<?php
/*
* @package Whoischecker
*/

session_start();

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php'))
{
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

if (class_exists('Inc\\Base\\UploadFile'))
{
    $upload_file_class = Inc\Base\UploadFile::class;
}

$upload_file = new $upload_file_class();

$upload_file->uploadFile();
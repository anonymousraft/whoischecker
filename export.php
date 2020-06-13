<?php
/*
* @package Whoischecker
*/

session_start();

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php'))
{
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

if (class_exists('Inc\\Pages\\ExportData'))
{
    $export_data_class = Inc\Pages\ExportData::class;
}

$export_data = new $export_data_class();

$export_data->checkSession();

$export_data->exportData();

$export_data->filesCleanUp();

$export_data->sessionDestroy();
<?php
session_start();

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php'))
{
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

if (class_exists('Inc\\Pages\\BulkWhoisCheck'))
 {
    $bulkwhoischeck_class = Inc\Pages\BulkWhoisCheck::class;
 }

 $bulk_whois_check = new $bulkwhoischeck_class();

 $bulk_whois_check->template();

 $bulk_whois_check->checkSessionVar();

 $all_data = $bulk_whois_check->bulkWhoisCheck();

 $bulk_whois_check->getView();

 $bulk_whois_check->registerFooterScripts();
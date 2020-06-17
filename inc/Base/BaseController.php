<?php

/*
* @package Whoischecker
*/

namespace Inc\Base;

class BaseController
{

    public $app_name;
    public $app_url;
    public $page_titles = [];
    public $dns_record_types = [];

    public function __construct()
    {

        $this->app_root = $_SERVER['DOCUMENT_ROOT'] ;
        $this->app_url = $this->appURL();
        $this->pageTitles();
        $this->dnsRecordTypes();
    }

    private function appURL()
    {
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
        "https" : "http") . "://" . $_SERVER['HTTP_HOST'];

        return $link;
    }

    public function registerHeaderScripts()
    {
        require_once "$this->app_root/assets/layouts/header.php";
    }

    public function bodyHTML()
    {
        require_once "$this->app_root/assets/layouts/body.php";
    }

    public function  registerFooterScripts()
    {
        require_once "$this->app_root/assets/layouts/footer.php";
    }

    public function pageTitles()
    {
        $this->page_titles = [
            'home' => 'Bulk Whois Checker 2.0 by Hitendra',
            'results' => 'Results...',
            'dnscheck' => 'Check DNS Records'
        ];
    }

    public function dnsRecordTypes()
    {
        $this->dns_record_types = [
            'A',
            'CNAME',
            'HINFO',
            'CAA',
            'MX',
            'NS',
            'PTR',
            'SOA',
            'TXT',
            'AAAA',
            'SRV',
            'NAPTR',
            'A6'
        ];
    }

}
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
    public $dns_record_view = [];

    public function __construct()
    {

        $this->app_root = $_SERVER['DOCUMENT_ROOT'];
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
            'A' => DNS_A,
            'CNAME' => DNS_CNAME,
            'HINFO' => DNS_HINFO,
            'CAA' => DNS_CAA,
            'MX' => DNS_MX,
            'NS' => DNS_NS,
            'PTR' => DNS_PTR,
            'SOA' => DNS_SOA,
            'TXT' => DNS_TXT,
            'AAAA' => DNS_AAAA,
            'SRV' => DNS_SRV,
            'NAPTR' => DNS_NAPTR,
            'A6' => DNS_A6
        ];

        $this->dns_record_view = [
            'A' => 'ip',
            'CAA' => 'value',
            'CNAME' => '',
            'MX' => 'target',
            'NS' => 'target',
            'PTR' => '',
            'SOA' => [
                'mname',
                'rname'
            ],
            'TXT' => 'txt',
            'AAAA' => 'ipv6',
            'SRV' => '',
            'NAPTR' => '',
            'A6' => ''
        ];
    }

    public function debug($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        exit;
    }
}

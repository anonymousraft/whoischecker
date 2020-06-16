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

    public function __construct()
    {

        $this->app_root = $_SERVER['DOCUMENT_ROOT'] ;
        $this->app_url = $this->appURL();
        $this->pageTitles();
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
            'results' => 'Results...'
        ];
    }

    public function filterDomain(string $domain_name)
    {
        $domain = trim($domain_name); //remove space from start and end of domain

        //remove traling slash
        if (substr($domain, -1) == '/') {
            $domain = substr($domain, 0, -1);
        }

        // remove http:// if included
        if (substr(strtolower($domain), 0, 7) == "http://") {
            $domain = substr($domain, 7);
        }

        // remove https:// if included
        if (substr(strtolower($domain), 0, 8) == "https://") {
            $domain = substr($domain, 8);
        }

        //remove subdomain
        $domain = $this->giveHost($domain);

        return $domain;
    }

    public function giveHost($host_with_subdomain)
    {
        $cctld = '.co.uk';

        $array = explode(".", $host_with_subdomain);

        $tld = '.' . $array[count($array) - 2] . '.' . $array[count($array) - 1];

        if ($cctld == $tld) {
            return $array[count($array) - 3] . '.' . $array[count($array) - 2] . '.' . $array[count($array) - 1];
        }

        return (array_key_exists(count($array) - 2, $array) ? $array[count($array) - 2] : "") . "." . $array[count($array) - 1];
    }

    public function debug($string)
    {
        return '<pre>'. var_dump($string).'</pre>';
    }
}
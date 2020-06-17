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

    public function debug($string)
    {
        return '<pre>'. var_dump($string).'</pre>';
    }
}
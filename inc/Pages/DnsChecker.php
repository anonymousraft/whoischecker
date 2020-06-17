<?php

/*
* @package Whoischecker
*/

namespace Inc\Pages;

use \Inc\Base\BaseController;

class DnsChecker extends BaseController
{
    public function initiate()
    {

        $this->template();
    }

    public function template()
    {
        $this->registerHeaderScripts();
        echo '<title>' . $this->page_titles['dnscheck'] . '</title>';
        $this->bodyHTML();
        require_once "$this->app_root/views/dnscheck.php";
        $this->registerFooterScripts();
    }
}
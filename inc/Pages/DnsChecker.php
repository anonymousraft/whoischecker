<?php

/*
* @package Whoischecker
*/

namespace Inc\Pages;

use \Inc\Base\FilterText;
use \Inc\Base\BaseController;

class DnsChecker extends BaseController
{
    public $filter_text;

    public function initiate()
    {
        $this->template();
    }

    public function getFormData(string $domain_name, string $dns_record_type)
    {
        $this->filter_text = new FilterText();
        
        $domain = $this->filter_text->is_url($domain_name);

        if (!$domain) {
            echo 'Please Enter a Valid Domain Name: eg. quatervois.io or https://quatervois.io.';
            die();
        }

        $this->checkDnsRecord($this->filter_text->filterDomain($domain),$dns_record_type);
    }

    public function template()
    {
        $this->registerHeaderScripts();
        echo '<title>' . $this->page_titles['dnscheck'] . '</title>';
        $this->bodyHTML();
        require_once "$this->app_root/views/dnscheck.php";
        $this->registerFooterScripts();
    }

    public function checkDnsRecord(string $domain_name, string $dns_record_type)
    {
        $int_type = $this->dns_record_types[$dns_record_type];

        $dns_record = dns_get_record($domain_name,$int_type);

        echo '<pre>';
        var_dump($dns_record);
        echo '</pre>';
        
    }
}
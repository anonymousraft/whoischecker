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
    public $dns_records;
    public $curl_url;
    public $cloudflare_dns_data = [];
    public $dns_record_type;
    public $domain_name;

    public function initiate()
    {
        $this->template();
    }

    public function getFormData(string $domain_name, string $dns_record_type)
    {
        $this->filter_text = new FilterText();
        $this->dns_record_type = $dns_record_type;
        $this->domain_name = $domain_name;

        $domain = $this->filter_text->is_url($this->domain_name);


        if (!$domain) {
            echo 'Please Enter a Valid Domain Name: eg. quatervois.io or https://quatervois.io.';
            die();
        }

        sleep(1);

        $this->curl_url = 'https://cloudflare-dns.com/dns-query?name='. $this->filter_text->filterDomain($domain).'&type='. $this->dns_record_type;

        $this->cloudflareDnsApi($this->curl_url);

    }

    public function template()
    {
        $this->registerHeaderScripts();
        echo '<title>' . $this->page_titles['dnscheck'] . '</title>';
        $this->bodyHTML();
        require_once "$this->app_root/views/dnscheck.php";
        $this->registerFooterScripts();
    }


    public function cloudflareDnsApi(string $url)
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/dns-json'));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($ch, CURLOPT_URL, $url);
        
        $result = curl_exec($ch);

        curl_close($ch);

        $this->cloudflare_dns_data = json_decode($result, true);

        $this->cloudflareApiView($this->cloudflare_dns_data);
    }

    public function cloudflareApiView(array $dns_data)
    {
        if (isset($dns_data['Answer'])) 
        {
            $answer_data = $dns_data['Answer'];

            echo '<h4>' . $this->dns_record_type . ' Records</h4>';

            foreach ($answer_data as $data) 
            {
                foreach ($data as $key => $value) 
                {
                    if ($key == 'data') 
                    {
                        echo '<p style="color: #087fb5;font-weight: 500;">' . $value . '</p>';
                    }
                }
            }
        } else {
            echo '<p style="color: #087fb5;font-weight: 500;">No <strong>' . $this->dns_record_type . '</strong>' . ' record found for ' . $this->domain_name . '</p>';
        }
    }
}

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

        sleep(1);

        $this->checkDnsRecord($this->filter_text->filterDomain($domain), $dns_record_type);
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

        $this->dns_records = dns_get_record($domain_name, $int_type);

        //$this->debug($this->dns_records);

        if (empty($this->dns_records)) {
            echo '<p style="color: #087fb5;font-weight: 500;">No <strong>' . $dns_record_type . '</strong>' . ' record found for ' . $domain_name . '</p>';
            exit;
        }

        $this->getView($this->dns_record_view[$dns_record_type], $dns_record_type);
    }

    public function getView($key_value, $dns_record_type)
    {

        if (is_array($key_value)) {
            $this->getSoaRecord($key_value);
            exit;
        }

        echo '<h4>' . $dns_record_type . ' Records</h4>';
        foreach ($this->dns_records as $dns_record) {
            
            foreach ($dns_record as $key => $value) {
                
                if ($key == $key_value) {
                    echo '<p style="color: #087fb5;font-weight: 500;">' . $value . '</p>';
                }
            }
        }
    }

    public function getSoaRecord(array $keys)
    {
        $record_type = $this->dns_records[0][$keys[0]];

        if ($record_type === 'SOA') {
            echo '<h4>' . $record_type . ' Records</h4>';
            foreach ($this->dns_records as $dns_record) {
                foreach ($dns_record as $key => $value) {
                    if ($key === $keys[1]) {
                        echo '<p style="color: #087fb5;font-weight: 500;">' . $value . '</p>';
                    } elseif ($key === $keys[2]) {
                        echo '<p style="color: #087fb5;font-weight: 500;">' . $value . '</p>';
                    }
                }
            }
        }

    }
}

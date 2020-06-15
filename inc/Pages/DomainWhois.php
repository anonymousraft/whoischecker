<?php

/*
* @package Whoischecker
*/

namespace Inc\Pages;

use \Inc\Base\BaseController;
use \Inc\Base\FilterText;
use \Inc\Base\WhoisServer;

class DomainWhois extends BaseController
{
    public $whois;
    public $filter_text;
    public $domain_data;

    public function initiate(string $domain_name)
    {
        $this->template();

        $this->domainWhoisCheck($this->domainSanitization($domain_name));

        $this->getView();

        $this->registerFooterScripts();
    }

    public function template()
    {
        $this->whois = new WhoisServer();
        $this->filter_text = new FilterText();

        $this->registerHeaderScripts();
        echo '<title>' . $this->page_titles['results'] . '</title>';
        $this->bodyHTML();
    }

    public function domainSanitization($domain_name)
    {
        if (empty($domain_name)) {
            echo 'Please Enter a Valid Domain Name: eg. quatervois.io or https://quatervois.io.<a href="index.php"> <<< Home</a>';
            die();
        }

        $input = $this->is_url($domain_name);

        if (!$input) {
            echo 'Please Enter a Valid Domain Name: eg. quatervois.io or https://quatervois.io. <a href="index.php"> <<< Home</a>';
            die();
        }

        return $input;
    }

    public function domainWhoisCheck($domain)
    {
        $whois_data = $this->whois->whoislookup($domain);

        if (strpos($whois_data, 'Error: No appropriate') !== false) {
            echo $whois_data . '<a href="index.php">' . ' <<< Home' . '</a>';
            die();
        }

        $this->domain_data = $this->filter_text->get_filtered_data($whois_data);
    }

    public function is_url($uri)
    {
        $domain_validation = '/((http|https)\:\/\/)?[a-zA-Z0-9\.\/\?\:@\-_=#]+\.([a-zA-Z0-9\&\.\/\?\:@\-_=#])*/';
        if (preg_match($domain_validation, $uri)) {
            return $uri;
        } else {
            return false;
        }
    }

    public function getView()
    {
        require_once "$this->app_root/views/singledomain.php";
    }


    public function viewData()
    {
        echo '<tr><th>Domain Name</th><th>Whois Registrar</th><th>Registrar URL</th><th>Update Date</th><th>Update Time</th><th>Created Date</th><th>Created Time</th><th>Expiry Date</th><th>Expiry Time</th><th>Error</th></tr>';
        echo '<tr>';
        foreach ($this->domain_data as $value) {
            echo '<td>' . $value . '</td>';
        }
        echo '</tr>';
    }
}
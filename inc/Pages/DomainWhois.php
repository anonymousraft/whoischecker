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

    public function template()
    {
        $this->whois = new WhoisServer();
        $this->filter_text = new FilterText();

        $this->registerHeaderScripts();
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

        $domain_data = $this->filter_text->get_filtered_data($whois_data);

        return $domain_data;
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

    public function viewData($domain_data)
    {
        echo '<tr><th>Domain Name</th><th>Whois Registrar</th><th>Registrar URL</th><th>Update Date</th><th>Update Time</th><th>Created Date</th><th>Created Time</th><th>Expiry Date</th><th>Expiry Time</th><th>Error</th></tr>';
        echo '<tr>';
                    foreach($domain_data as $value)
                    {
                        echo '<td>'. $value .'</td>';
                    }
        echo '</tr>';
    }
}

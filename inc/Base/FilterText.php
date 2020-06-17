<?php

/*
* @package Whoischecker
*/

namespace Inc\Base;

class FilterText
{
    public function get_filtered_data($whoisdata)
    {
            $result = $whoisdata;

            $whoisrecord = substr($result, 0, strpos($result, "<<<"));

            $domain_name =  strtolower($this->get_domain_data($whoisrecord,'Registry','Domain Name:',13));

            $whois_server = $this->get_domain_data($whoisrecord,'Registrar URL','Registrar WHOIS Server:',24);
            $whois_server_url = $this->get_domain_data($whoisrecord,'Updated Date','Registrar URL',14);

            $update = $this->get_date_time($this->get_domain_data($whoisrecord,'Creation Date','Updated Date:',14));
            $update_date = $update['date'];
            $update_time = $update['time'];

            $created = $this->get_date_time($this->get_domain_data($whoisrecord,'Registry Expiry Date','Creation Date:',14));
            $created_date = $created['date'];
            $created_time = $created['time'];


            $expiry_first_half = $this->get_domain_data($whoisrecord,'Registrar Registration Expiration Date','Expiry Date:',13);
            $expiry = $this->get_date_time($this->addition_cut($expiry_first_half, 'Registrar'));
            $expiry_date = $expiry['date'];
            $expiry_time = $expiry['time'];
            
            $export_data = array(
                'Domain Name' => trim($domain_name),
                'Whois Server' => trim($whois_server),
                'Registrar URL' => trim($whois_server_url),
                'Update Date' => trim($update_date),
                'Update Time' => trim($update_time),
                'Creation Date' => trim($created_date),
                'Creation Time' => trim($created_time),
                'Expiry Date' => trim($expiry_date),
                'Expiry Time' => trim($expiry_time),
                'Error' => ''
            );

            return $export_data;
    }

    private function get_domain_data($whois, $str_cut_from, $str_cut,$int_pos)
    {
        list($filter_text) = explode($str_cut_from, $whois);
        $result = substr($filter_text, strpos($filter_text, $str_cut)+$int_pos);
        return $result;
    }

    private function addition_cut($string_to_cut, $str_by_cut){

        list($filter_text) = explode($str_by_cut, $string_to_cut);
        $result = (string) $filter_text;

        return $result;
    }

    private function get_date_time($raw_date){

        $date = date('c', strtotime($raw_date));

        $t_obj = new \DateTime($date);
        $date = $t_obj->format('Y-m-d');
        $time = $t_obj->format('H:i:s');
        $timezone = $t_obj->getTimezone()->getName(); 

        return [
            'date' => $date,
            'time' => $time,
            'timezone' => $timezone
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

    public function is_url($uri)
    {
        $domain_validation = '/((http|https)\:\/\/)?[a-zA-Z0-9\.\/\?\:@\-_=#]+\.([a-zA-Z0-9\&\.\/\?\:@\-_=#])*/';
        if (preg_match($domain_validation, $uri)) {
            return $uri;
        } else {
            return false;
        }
    }

}

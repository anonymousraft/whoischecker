<?php

/*
* @package Whoischecker
*/

namespace Inc\Pages;

use \Inc\Base\FilterText;
use \Inc\Base\WhoisServer;
use \Inc\Base\BaseController;

class BulkWhoisCheck extends BaseController
{
    public $whois;
    public $filter_text;
    public $all_data = [];
    public $sleep_time;

    public function initiate($get_sleep_time = 250000)
    {
        $this->sleep_time = $get_sleep_time;

        $this->template();

        $this->checkSessionVar();

        $this->bulkWhoisCheck();

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

    public function checkSessionVar()
    {
        if (!$_SESSION['upload']) {
            echo 'Please upload a CSV file first. <a href="index.php"><< Home</a>';
            die();
        }
    }

    public function bulkWhoisCheck()
    {

        if (($handle = fopen("$this->app_root/upload/domains.csv", "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $all_domain_names[] = $this->filter_text->filterDomain($data[0]);
            }

            $unique_domains = array_unique($all_domain_names, SORT_STRING);

            $unique_domains_reset = array_values($unique_domains);

            $num = count($unique_domains_reset);

            for ($c = 0; $c < $num; $c++) {

                usleep($this->sleep_time);

                $result = $this->whois->whoislookup($unique_domains_reset[$c]);

                if (strpos($result, 'Error: No appropriate') !== false) {
                    $export_data = array(
                        'Domain Name' => trim($data[$c]),
                        'Whois Server' => '',
                        'Registrar URL' => '',
                        'Update Date' => '',
                        'Update Time' => '',
                        'Creation Date' => '',
                        'Creation Time' => '',
                        'Expiry Date' => '',
                        'Expiry Time' => '',
                        'Error' => trim($result)
                    );
                } else {
                    $export_data = $this->filter_text->get_filtered_data($result);
                }

                $this->all_data[] = $export_data;
            }

            $_SESSION["domain_data"] = $this->all_data;

            fclose($handle);
        }
    }

    public function getView()
    {
        require_once "$this->app_root/views/bulkwhoisview.php";
    }

    public function viewData()
    {

        $flag = 0;

        foreach ($this->all_data as $data) {

            if ($flag === 0) 
            {
                echo '<tr>';
                foreach ($data as $col_head => $value) {
                    echo '<th>' . $col_head . '</th>';
                }
                echo '</tr>';
                $flag = 1;
            }

            echo '<tr>';
            foreach ($data as $col_head => $value) {
                echo '<td>' . $value . '</td>';
            }
            echo '</tr>';
        }
    }
}

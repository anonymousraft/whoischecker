<?php

/*
* @package Whoischecker
*/

namespace Inc\Pages;

use \Inc\Base\BaseController;
use \Inc\Base\FilterText;
use \Inc\Base\WhoisServer;

class BulkWhoisCheck extends BaseController
{
    public $whois;
    public $filter_text;
    public $all_data = [];

    public function initiate()
    {
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
        $row = 1;
        if (($handle = fopen("$this->app_root/upload/domains.csv", "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $num = count($data);
                $row++;

                for ($c = 0; $c < $num; $c++) {
                    $result = $this->whois->whoislookup($data[$c]);

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

                    $this->all_data[] = $export_data; //Array with all the extracted data.
                }
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
        echo '<tr><th>Domain Name</th><th>Whois Registrar</th><th>Registrar URL</th><th>Update Date</th><th>Update Time</th><th>Created Date</th><th>Created Time</th><th>Expiry Date</th><th>Expiry Time</th><th>Error</th></tr>';
        foreach ($this->all_data as $data) {
            echo '<tr>';
            foreach ($data as $col_head => $value) {
                echo '<td>' . $value . '</td>';
            }
            echo '</tr>';
        }
    }
}

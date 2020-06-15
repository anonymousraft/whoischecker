<?php

/*
* @package Whoischecker
*/

namespace Inc\Pages;

use \Inc\Base\BaseController;

class ExportData extends BaseController
{

    public function initiate()
    {
        $this->checkSession();

        $this->exportData();

        $this->filesCleanUp();

        $this->sessionDestroy();
    }

    public function checkSession()
    {
        if (!isset($_SESSION["domain_data"])) {
            echo 'No data found in the records, Please upload file from <a href="index.php">here</a>.';
            die();
        }
    }

    public function exportData()
    {
        $fileName = "whois_data-". date("Y-m-d-his").  ".xls";

        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Cache-Control: max-age=0");


        $flag = false;

        foreach ($_SESSION["domain_data"] as $row) {
            if (!$flag) {
                // display column names as first row
                echo implode("\t", array_keys($row)) . "\n";
                $flag = true;
            }
            // filter data
            array_walk($row, [$this, 'filterData']);

            echo implode("\t", array_values($row)) . "\n";
        }
    }

    public function filterData($str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }

    public function filesCleanUp()
    {
        $uploaded_file = "$this->app_root/upload/domains.csv";
        unlink($uploaded_file);
        rmdir("$this->app_root/upload");
    }

    public function sessionDestroy()
    {
        $_SESSION = array();
        session_destroy();
        exit;
    }
}

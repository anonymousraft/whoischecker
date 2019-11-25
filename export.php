<?php
//Exporting data to excel sheet
session_start();
$fileName = "domain_data" . rand(1,100) . ".xls";

if ($_SESSION["domain_data"]) {
    function filterData($str) {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }

    // headers for download
   header("Content-Disposition: attachment; filename=\"$fileName\"");
   header("Content-Type: application/vnd.ms-excel");


    $flag = false;
    foreach($_SESSION["domain_data"] as $row) {
        if(!$flag) {
            // display column names as first row
            echo implode("\t", array_keys($row)) . "\n";
            $flag = true;
        }
        // filter data
        array_walk($row, 'filterData');
        echo implode("\t", array_values($row)) . "\n";
    }
    exit;
}
?>

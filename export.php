<?php
//Exporting data to excel sheet
session_start();

if(!isset($_SESSION["domain_data"]))
{
    echo 'No data found in the records, Please upload file from <a href="index.php">here</a>.';
    die();
}

$fileName = "domain_data" . rand(1,100) . ".xls";


function filterData($str) 
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}


header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Cache-Control: max-age=0");


$flag = false;

foreach($_SESSION["domain_data"] as $row) 
{
    if(!$flag) 
    {
            // display column names as first row
        echo implode("\t", array_keys($row)) . "\n";
        $flag = true;
    }
        // filter data
    array_walk($row, 'filterData');
    echo implode("\t", array_values($row)) . "\n";
}

//deleting uplaoded file
$uploaded_file = "upload/domains.csv";
unlink($uploaded_file);
rmdir('upload');

$_SESSION = array();
session_destroy();
exit;

?>

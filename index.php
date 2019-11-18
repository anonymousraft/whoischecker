<?php
session_start();
require("whoisServer.php");
$whois=new Whois;
//Reading CSV File
$row = 1;
if (($handle = fopen("domains.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
        for ($c=0; $c < $num; $c++) {
            
            //echo $data[$c] . "<br /><br />\n";
            //calling the whois lookup function
            $result = $whois->whoislookup($data[$c]);
            $whoisrecord = substr($result, 0, strpos($result, "<<<"));
            //$whatIWant = substr($variable, strpos($variable, "Updated Date:"));    

            //Getting Domain Name
            list($domainname) = explode('Registry', $whoisrecord);
            //list($SoleDomain) = explode('Domain Name:', $domainname);
            $SoleDomain = substr($domainname, strpos($domainname, "Domain Name:")+13);
           // echo trim($SoleDomain)."<br/>";//variable containing domain name.

            //Getting WhoisServer
            list($WhoisServer) = explode('Registrar URL', $whoisrecord);
            $whatIWant = substr($WhoisServer, strpos($WhoisServer, "Registrar WHOIS Server:")+24);
           // echo trim($whatIWant)."<br/>";

            //Getting Whois Server URL
            $WhoisServerURL = substr($whoisrecord, strpos($whoisrecord, "Registrar URL"));
            list($WhoisServerURLFinal) = explode('Updated Date', $WhoisServerURL);
            $onlyServerURL = substr($WhoisServerURLFinal, strpos($WhoisServerURLFinal, "Registrar URL")+14);
            //echo trim($onlyServerURL)."<br/>";

            //getting updated date
            $UpdatedDate = substr($whoisrecord, strpos($whoisrecord, "Updated Date"));
            list($UpdatedDatewithtime) = explode('Creation Date', $UpdatedDate);
            list($UpdatedDatewithouttime) = explode('T', $UpdatedDatewithtime);
            $SoleUpdatedDate = substr($UpdatedDatewithouttime, strpos($UpdatedDatewithouttime, "Updated Date:")+14);
            //echo trim($SoleUpdatedDate)."<br/>";

            //Creation Date:
            $CreatedDate = substr($whoisrecord, strpos($whoisrecord, "Creation Date"));
   			list($CreationDatewithtime) = explode('Registry Expiry Date', $CreatedDate);
   			list($CreationDatewithouttime) = explode('T', $CreationDatewithtime);
   			$SoleCreationdDate = substr($CreationDatewithouttime, strpos($CreationDatewithouttime, "Creation Date:")+14);
   			//echo trim($SoleCreationdDate)."<br/>";

   			//Expiery Date:
   			$ExpiryDate = substr($whoisrecord, strpos($whoisrecord, "Expiry Date"));
   			list($ExpiryDatewithtime) = explode('Registrar Registration Expiration Date', $ExpiryDate);
   			list($ExpiryDatewithouttime) = explode('T', $ExpiryDatewithtime);
   			$SoleExpiryDate = substr($ExpiryDatewithouttime, strpos($ExpiryDatewithouttime, "Expiry Date:")+13);
   			//echo trim($SoleExpiryDate)."<br/>";

   			//Data Prepration for Export
   			$export_data = array(
   				'Domain Name' => trim($SoleDomain),
   				'Whois Server URL' => trim($whatIWant),
   				'Registrar URL' => trim($onlyServerURL),
   				'Updated Date' => trim($SoleUpdatedDate),
   				'Creation Date' => trim($SoleCreationdDate),
   				'Expiry Date' => trim($SoleExpiryDate)
   		);
   			$finalarray[] = $export_data; //Array with all the extracted data.
   			$_SESSION["domain_data"] = $finalarray;

   			//echo "<br /><br />\n";

            //echo $whoisrecord;
        }
    }
    fclose($handle);
}

//Exporting data to excel sheet
/*
$fileName = "domain_data" . rand(1,100) . ".xls";

if ($finalarray) {
    function filterData($str) {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }

    // headers for download
   header("Content-Disposition: attachment; filename=\"$fileName\"");
   header("Content-Type: application/vnd.ms-excel");


    $flag = false;
    foreach($finalarray as $row) {
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
*/
echo "<h2 align=\"center\">Records Succsessfully Factched</h2>";
echo "<h4 align=\"center\"><a href=\"export.php\">Export Spreadsheet</a></h4>"
?>

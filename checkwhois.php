<?php
session_start();

require_once "assets/layouts/header.php";

require_once "assets/layouts/titles.php";

echo '<title>'.$resultPageTitle.'</title>';

require_once "assets/layouts/body.php";

if(!$_SESSION['upload'])
{
    echo 'Please upload a CSV file first. <a href="index.php"><< Home</a>';
    die();
}

require_once "whoisServer.php";

require_once "filterText.php";

$whois=new Whois;

$filter_text = new filterText();

/*
Reading CSV File
 */
$row = 1;
if (($handle = fopen("upload/domains.csv", "r")) !== false) 
{
    while (($data = fgetcsv($handle, 1000, ",")) !== false) 
    {
        $num = count($data);
        $row++;

        for ($c=0; $c < $num; $c++) 
        {
            $result = $whois->whoislookup($data[$c]);

            if(strpos($result, 'Error: No appropriate') !== false)
            {
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
            }
            else
            {
                $export_data = $filter_text->get_filtered_data($result);
            }
           
            $all_data[] = $export_data; //Array with all the extracted data.
        }
    }

    $_SESSION["domain_data"] = $all_data;

    fclose($handle);
}
?>
<div class="container-fluid div-def-padding">
    <div class="row h-100">
        <div class="col-md-12 col-lg-12">
            <h2 align="center">Records Succsessfully Factched</h2>
            <table class="records-table">
                <?php
                echo '<tr><th>Domain Name</th><th>Whois Registrar</th><th>Registrar URL</th><th>Update Date</th><th>Update Time</th><th>Created Date</th><th>Created Time</th><th>Expiry Date</th><th>Expiry Time</th><th>Error</th></tr>';
                foreach ($all_data as $data) 
                {
                    echo '<tr>';
                    foreach($data as $col_head => $value)
                    {
                        echo '<td>'. $value .'</td>';
                    }
                    echo '</tr>';
                }
                ?>
            </table>
            <hr/>
        </div>
        <div class="col-md-12 col-lg-12">
            <div class="card card-block w-25">
                <h4 align="center"><a href="export.php" style="color: #03A9F4; text-decoration: none !important; padding: 10px; border: solid 2px #03A9F4;border-radius: 5px;">Export Spreadsheet</a></h4>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid div-def-padding div-center" style="padding-top:0px !important">
  <div class="row h-100">
    <div class="col-md-12 col-lg-12">
       <div class="card card-block w-25">
         <a href="index.php"><< Go Back to home</a>
     </div>
 </div>
</div>
</div>
<?php include("assets/layouts/footer.php"); ?>

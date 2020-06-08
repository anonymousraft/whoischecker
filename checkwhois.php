<?php 
require_once "assets/layouts/header.php";

require_once "assets/layouts/titles.php";

echo '<title>'.$resultPageTitle.'</title>';

require_once "assets/layouts/body.php";

require_once "whoisServer.php";

session_start();

$whois=new Whois;

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
            $whoisrecord = substr($result, 0, strpos($result, "<<<"));

            $domain_name = get_domain_data($whoisrecord,'Registry','Domain Name:',13);
            $whois_server = get_domain_data($whoisrecord,'Registrar URL','Registrar WHOIS Server:',24);
            $whois_server_url = get_domain_data($whoisrecord,'Updated Date','Registrar URL',14);

            $update = get_date_time(get_domain_data($whoisrecord,'Creation Date','Updated Date:',14));
            $update_date = $update['date'];
            $update_time = $update['time'];

            $created = get_date_time(get_domain_data($whoisrecord,'Registry Expiry Date','Creation Date:',14));
            $created_date = $created['date'];
            $created_time = $created['time'];


            $expiry_first_half = get_domain_data($whoisrecord,'Registrar Registration Expiration Date','Expiry Date:',13);
            $expiry = get_date_time(addition_cut($expiry_first_half, 'Registrar'));
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
                'Expiry Time' => trim($expiry_time)
            );
            $all_data[] = $export_data; //Array with all the extracted data.
        }
    }

    $_SESSION["domain_data"] = $all_data;

    fclose($handle);
}

function get_domain_data($whois, $str_cut_from, $str_cut,$int_pos)
{
    list($filter_text) = explode($str_cut_from, $whois);
    $result = substr($filter_text, strpos($filter_text, $str_cut)+$int_pos);
    return $result;
}

function addition_cut($string_to_cut, $str_by_cut){

    list($filter_text) = explode($str_by_cut, $string_to_cut);
    $result = (string) $filter_text;

    return $result;
}

function get_date_time($raw_date){
    
    $date = date('c', strtotime($raw_date));

    $t_obj = new DateTime($date);
    $date = $t_obj->format('Y-m-d');
    $time = $t_obj->format('H:i:s');
    $timezone = $t_obj->getTimezone()->getName(); 

    return [
        'date' => $date,
        'time' => $time,
        'timezone' => $timezone
        ];
}
?>
<div class="container-fluid div-def-padding">
<div class="row h-100">
<div class="col-md-12 col-lg-12">
    <table>
        <?php
        echo '<tr><td>Domain Name</td><td>Whois Registrar</td><td>Registrar URL</td><td>Update Date</td><td>Update Time</td><td>Created Date</td><td>Created Time</td><td>Expiry Date</td><td>Expiry Time</td></tr>';
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
<div class="card card-block w-25">
<h2 align="center">Records Succsessfully Factched</h2>
<hr/>
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

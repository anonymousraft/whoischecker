<?php

require_once "assets/layouts/header.php";

require_once "assets/layouts/titles.php";

echo '<title>'.$resultPageTitle.'</title>';

require_once "assets/layouts/body.php";

if(empty($_POST['domain_name']))
{
  echo 'Please Enter a Valid Domain Name: eg. quatervois.io or https://quatervois.io.<a href="index.php"> <<< Home</a>';
  die();
}

$input = is_url($_POST['domain_name']);

if(!$input){
    echo 'Please Enter a Valid Domain Name: eg. quatervois.io or https://quatervois.io. <a href="index.php"> <<< Home</a>';
    die();
}

require_once 'whoisServer.php';

require_once "filterText.php";

$whois = new Whois();
$filter_text = new filterText();

$whois_data = $whois->whoislookup($input);

$domain_data = $filter_text->get_filtered_data($whois_data);

function is_url($uri)
{
    $domain_validation = '/((http|https)\:\/\/)?[a-zA-Z0-9\.\/\?\:@\-_=#]+\.([a-zA-Z0-9\&\.\/\?\:@\-_=#])*/';
    if(preg_match($domain_validation,$uri))    
    {
      return $uri;
    }
    else{
        return false;
    }
}
?>
<div class="container-fluid div-def-padding">
    <div class="row h-100">
        <div class="col-md-12 col-lg-12">
            <h2 align="center">Record Succsessfully Factched</h2>
            <table class="records-table">
                <?php
                echo '<tr><th>Domain Name</th><th>Whois Registrar</th><th>Registrar URL</th><th>Update Date</th><th>Update Time</th><th>Created Date</th><th>Created Time</th><th>Expiry Date</th><th>Expiry Time</th></tr>';
                echo '<tr>';
                    foreach($domain_data as $value)
                    {
                        echo '<td>'. $value .'</td>';
                    }
                echo '</tr>';
                ?>
            </table>
            <hr/>
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
<?php
require_once "assets/layouts/footer.php";
?>

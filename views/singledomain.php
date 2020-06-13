<?php

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php'))
{
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

if (class_exists('Inc\\Pages\\DomainWhois'))
 {
    $domain_whois_class = Inc\Pages\DomainWhois::class;
 }

 $domain_whois = new $domain_whois_class();

?>
<div class="container-fluid div-def-padding">
    <div class="row h-100">
        <div class="col-md-12 col-lg-12">
            <h2 align="center">Record Succsessfully Factched</h2>
            <table class="records-table">
                <?php
                $domain_whois->viewData();
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
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

$domain_whois->template();

$domain_data = $domain_whois->domainWhoisCheck($domain_whois->domainSanitization($_POST['domain_name']));

$domain_whois->getView();

$domain_whois->registerFooterScripts();


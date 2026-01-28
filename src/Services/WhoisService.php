<?php

declare(strict_types=1);

namespace App\Services;

use Iodev\Whois\Factory;
use Iodev\Whois\Whois;
use Iodev\Whois\Modules\Tld\DomainInfo;

class WhoisService
{
    private Whois $whois;

    public function __construct()
    {
        $this->whois = Factory::get()->createWhois();
    }

    public function lookup(string $domain)
    {
        try {
            return $this->whois->loadDomainInfo($domain);
        } catch (\Exception) {
            return null;
        }
    }

    public function filterDomain(string $url): string
    {
        $url = trim($url);
        $url = str_replace(['http://', 'https://', 'www.'], '', $url);
        return explode('/', $url)[0];
    }
}

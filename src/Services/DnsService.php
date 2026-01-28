<?php

declare(strict_types=1);

namespace App\Services;

use App\Config;

class DnsService
{
    public function query(string $domain, string $type): array
    {
        try {
            $baseUrl = Config::dnsProviderUrl();
            $timeout = Config::get('DNS_TIMEOUT', 10);

            $url = $baseUrl . "?name=" . urlencode($domain) . "&type=" . urlencode($type);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['accept: application/dns-json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                $error = curl_error($ch);
                curl_close($ch);
                return ['error' => 'DNS query failed: ' . $error];
            }

            curl_close($ch);
            return json_decode($result, true) ?? ['error' => 'Invalid response'];
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }
}

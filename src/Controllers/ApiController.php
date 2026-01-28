<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\WhoisService;
use App\Services\DnsService;

class ApiController extends BaseController
{
    public function lookup(): void
    {
        try {
            $domain = $_POST['domain'] ?? '';
            if (empty($domain)) {
                $this->json(['success' => false, 'error' => 'Domain is required'], 400);
                return;
            }

            $whoisService = new WhoisService();
            $domain = $whoisService->filterDomain($domain);
            $info = $whoisService->lookup($domain);

            if (!$info) {
                $this->json(['success' => false, 'error' => 'Entity not found or lookup failed'], 404);
                return;
            }

            // Build comprehensive data array
            $nameServers = $info->getNameServers();
            $states = $info->getStates();

            $data = [
                'Domain Name' => $info->getDomainName(),
                'Registrar' => $info->getRegistrar() ?? 'N/A',
                'Owner' => $info->getOwner() ?? 'N/A',
                'Creation Date' => $info->getCreationDate() ? date("Y-m-d", $info->getCreationDate()) : 'N/A',
                'Updated Date' => method_exists($info, 'getUpdatedDate') && $info->getUpdatedDate() ? date("Y-m-d", $info->getUpdatedDate()) : 'N/A',
                'Expiry Date' => $info->getExpirationDate() ? date("Y-m-d", $info->getExpirationDate()) : 'N/A',
                'Whois Server' => $info->getWhoisServer() ?? 'N/A',
                'Name Servers' => is_array($nameServers) ? implode(', ', $nameServers) : 'N/A',
                'Status' => is_array($states) ? implode(', ', $states) : 'N/A',
                'DNSSEC' => method_exists($info, 'getDnssec') ? ($info->getDnssec() ?? 'N/A') : 'N/A'
            ];

            $this->json(['success' => true, 'data' => $data]);
        } catch (\Throwable $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function dnsQuery(): void
    {
        try {
            $domain = $_POST['domain'] ?? '';
            $type = $_POST['type'] ?? 'A';

            if (empty($domain)) {
                $this->json(['success' => false, 'error' => 'Domain is required'], 400);
                return;
            }

            $dnsService = new DnsService();
            $result = $dnsService->query($domain, $type);

            if (isset($result['error'])) {
                $this->json(['success' => false, 'error' => $result['error']], 500);
                return;
            }

            $this->json(['success' => true, 'data' => $result]);
        } catch (\Throwable $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function bulkProcess(): void
    {
        // For simplicity in this v5.0 SPA model, we'll process via individual /api/lookup calls
        // from the frontend, but we can add session-based batch management here if needed.
        $this->json(['success' => true, 'message' => 'Bulk engine ready']);
    }

    private function json(array $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}

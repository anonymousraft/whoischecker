<?php

declare(strict_types=1);

namespace App\Services;

class MailSecurityService
{
    private DnsService $dnsService;

    public function __construct()
    {
        $this->dnsService = new DnsService();
    }

    public function analyze(string $domain): array
    {
        // 1. Get SPF Record
        $spfData = $this->getSpf($domain);

        // 2. Get DMARC Record
        $dmarcData = $this->getDmarc($domain);

        // 3. Assess Vulnerability
        $assessment = $this->assessVulnerability($spfData, $dmarcData);

        return [
            'domain' => $domain,
            'spf' => $spfData,
            'dmarc' => $dmarcData,
            'assessment' => $assessment
        ];
    }

    private function getSpf(string $domain): array
    {
        $records = $this->dnsService->query($domain, 'TXT');

        if (isset($records['error'])) {
            return ['status' => 'error', 'raw' => null, 'error' => $records['error']];
        }

        $spfRecord = null;
        if (isset($records['Answer'])) {
            foreach ($records['Answer'] as $record) {
                $txt = trim($record['data'], '"');
                if (stripos($txt, 'v=spf1') === 0) {
                    $spfRecord = $txt;
                    break;
                }
            }
        }

        if (!$spfRecord) {
            return ['status' => 'missing', 'raw' => null];
        }

        return [
            'status' => 'found',
            'raw' => $spfRecord,
            'all_mechanism' => $this->parseSpfAll($spfRecord)
        ];
    }

    private function getDmarc(string $domain): array
    {
        // DMARC is always at _dmarc.domain
        $records = $this->dnsService->query("_dmarc.{$domain}", 'TXT');

        if (isset($records['error'])) {
            return ['status' => 'error', 'raw' => null, 'error' => $records['error']];
        }

        $dmarcRecord = null;
        if (isset($records['Answer'])) {
            foreach ($records['Answer'] as $record) {
                $txt = trim($record['data'], '"');
                if (stripos($txt, 'v=DMARC1') === 0) {
                    $dmarcRecord = $txt;
                    break;
                }
            }
        }

        if (!$dmarcRecord) {
            return ['status' => 'missing', 'raw' => null];
        }

        return [
            'status' => 'found',
            'raw' => $dmarcRecord,
            'policy' => $this->parseDmarcPolicy($dmarcRecord)
        ];
    }

    private function parseSpfAll(string $record): string
    {
        if (str_contains($record, '-all'))
            return 'Hard Fail (Strict)';
        if (str_contains($record, '~all'))
            return 'Soft Fail (Relaxed)';
        if (str_contains($record, '?all'))
            return 'Neutral (Unsafe)';
        if (str_contains($record, '+all'))
            return 'Allow All (Dangerous)';
        return 'Implicit Neutral (Unsafe)';
    }

    private function parseDmarcPolicy(string $record): string
    {
        if (preg_match('/p=(none|quarantine|reject)/', $record, $matches)) {
            return match ($matches[1]) {
                'reject' => 'Reject (Secure)',
                'quarantine' => 'Quarantine (Cautious)',
                'none' => 'None (Monitoring Only)',
                default => 'Unknown'
            };
        }
        return 'Unknown';
    }

    private function assessVulnerability(array $spf, array $dmarc): array
    {
        $score = 0; // 0 = Secure, 10 = Vulnerable
        $messages = [];
        $status = 'Secure'; // Secure, Moderate, Vulnerable

        // SPF Check
        if ($spf['status'] === 'missing') {
            $score += 5;
            $messages[] = 'Missing SPF record allows anyone to spoof this domain.';
        } elseif (str_contains($spf['all_mechanism'] ?? '', 'Unsafe') || str_contains($spf['all_mechanism'] ?? '', 'Dangerous')) {
            $score += 3;
            $messages[] = 'SPF policy is too permissive (' . $spf['all_mechanism'] . ').';
        }

        // DMARC Check
        if ($dmarc['status'] === 'missing') {
            $score += 5;
            $messages[] = 'Missing DMARC record makes it impossible to enforce spoofing protection.';
        } else {
            $policy = $dmarc['policy'];
            if (str_contains($policy, 'None')) {
                $score += 4;
                $messages[] = 'DMARC policy is set to "none". Fake emails are NOT blocked.';
            } elseif (str_contains($policy, 'Quarantine')) {
                $score += 1;
                $messages[] = 'DMARC policy is "quarantine". Spoofed emails may go to spam folder.';
            }
        }

        // Final Assessment
        if ($score >= 5) {
            $status = 'Vulnerable'; // High risk
        } elseif ($score > 0) {
            $status = 'Moderate'; // Some risk
        }

        if ($score === 0) {
            $messages[] = 'Domain is well-protected against direct spoofing.';
        }

        return [
            'score' => $score,
            'status' => $status,
            'summary' => implode(' ', $messages)
        ];
    }
}

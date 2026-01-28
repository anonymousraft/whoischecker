<?php

declare(strict_types=1);

namespace App;

/**
 * Application Configuration
 * 
 * Environment variables override these defaults.
 * Create a .env file in the project root to customize.
 */
class Config
{
    private static array $defaults = [
        // Application
        'APP_NAME' => 'Carbon Intelligence',
        'APP_ENV' => 'production',
        'APP_DEBUG' => false,

        // Bulk Processing
        'BULK_DOMAIN_LIMIT' => 5000,        // Max domains per bulk upload
        'BULK_RATE_DELAY' => 250,           // Delay between requests (ms)

        // WHOIS Settings
        'WHOIS_TIMEOUT' => 10,              // Timeout in seconds

        // DNS Settings
        'DNS_TIMEOUT' => 10,                // Timeout in seconds
        'DNS_PROVIDER' => 'cloudflare',     // cloudflare or google
    ];

    /**
     * Get a configuration value.
     * Priority: ENV variable > Default value
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        // Check environment variable first
        $envValue = $_ENV[$key] ?? getenv($key);

        if ($envValue !== false && $envValue !== null && $envValue !== '') {
            // Handle boolean strings
            if ($envValue === 'true')
                return true;
            if ($envValue === 'false')
                return false;
            // Handle numeric strings
            if (is_numeric($envValue))
                return (int) $envValue;
            return $envValue;
        }

        // Fall back to defaults, then provided default
        return self::$defaults[$key] ?? $default;
    }

    /**
     * Check if running in debug mode
     */
    public static function isDebug(): bool
    {
        return (bool) self::get('APP_DEBUG', false);
    }

    /**
     * Get application name
     */
    public static function appName(): string
    {
        return (string) self::get('APP_NAME', 'Carbon Intelligence');
    }

    /**
     * Get DNS provider URL
     */
    public static function dnsProviderUrl(): string
    {
        $provider = self::get('DNS_PROVIDER', 'cloudflare');

        return match ($provider) {
            'google' => 'https://dns.google/resolve',
            default => 'https://cloudflare-dns.com/dns-query',
        };
    }
}

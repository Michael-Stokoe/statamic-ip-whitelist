<?php

namespace Stokoe\IpWhitelist\Helpers;

class IpValidator
{
    public static function isValidIp(string $ip): bool
    {
        // Check for exact IP
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return true;
        }

        // Check for CIDR notation
        if (self::isValidCidr($ip)) {
            return true;
        }

        // Check for wildcard pattern
        if (self::isValidWildcard($ip)) {
            return true;
        }

        return false;
    }

    public static function isValidCidr(string $cidr): bool
    {
        if (!str_contains($cidr, '/')) {
            return false;
        }

        list($ip, $mask) = explode('/', $cidr, 2);

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        if (!is_numeric($mask) || $mask < 0 || $mask > 32) {
            return false;
        }

        return true;
    }

    public static function isValidWildcard(string $pattern): bool
    {
        if (!str_contains($pattern, '*')) {
            return false;
        }

        // Replace wildcards with valid IP parts for validation
        $testPattern = str_replace('*', '1', $pattern);
        
        return filter_var($testPattern, FILTER_VALIDATE_IP) !== false;
    }

    public static function normalizeIp(string $ip): string
    {
        return trim($ip);
    }

    public static function getIpType(string $ip): string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return 'exact';
        }

        if (self::isValidCidr($ip)) {
            return 'cidr';
        }

        if (self::isValidWildcard($ip)) {
            return 'wildcard';
        }

        return 'invalid';
    }
}

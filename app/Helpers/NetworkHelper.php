<?php
namespace App\Helpers;

class NetworkHelper
{
    /**
     * Check if an IP is within a CIDR range.
     * Supports single IP (exact match) or CIDR (e.g., 192.168.1.0/24).
     */
    public static function ipInRange(string $ip, string $cidrOrIp): bool
    {
        if (strpos($cidrOrIp, '/') === false) {
            // Exact IP match
            return $ip === $cidrOrIp;
        }
        [$subnet, $maskBits] = explode('/', $cidrOrIp, 2);
        $maskBits = (int)$maskBits;

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        if ($ipLong === false || $subnetLong === false) {
            return false;
        }
        $mask = -1 << (32 - $maskBits);
        $subnetMasked = $subnetLong & $mask;
        $ipMasked = $ipLong & $mask;
        return $subnetMasked === $ipMasked;
    }

    /**
     * Check if IP matches any of the provided ranges (IP or CIDR strings).
     */
    public static function ipAllowed(string $ip, array $ranges): bool
    {
        foreach ($ranges as $entry) {
            if (self::ipInRange($ip, trim($entry))) {
                return true;
            }
        }
        return false;
    }
}

<?php

namespace App\Services;

class IpAddressService
{
    /**
     * Get the real client IP address
     */
    public static function getRealIpAddress()
    {
        // Check for various headers that might contain the real IP
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',            // Proxy
            'HTTP_X_FORWARDED_FOR',      // Load Balancer/Proxy
            'HTTP_X_FORWARDED',          // Proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
            'HTTP_VIA',                  // Proxy
            'REMOTE_ADDR'                // Standard
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                
                // Handle comma-separated IPs (X-Forwarded-For can contain multiple IPs)
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                
                // Validate IP address (exclude private and reserved ranges for production)
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
                
                // In development, also accept private ranges
                if (filter_var($ip, FILTER_VALIDATE_IP) && !in_array($ip, ['127.0.0.1', '::1'])) {
                    return $ip;
                }
            }
        }

        // Fallback to request IP
        $requestIp = request()->ip();
        
        // If we're in local development, try to get a more meaningful IP
        if ($requestIp === '127.0.0.1' || $requestIp === '::1') {
            $localIp = self::getLocalNetworkIp();
            if ($localIp) {
                return $localIp;
            }
        }
        
        return $requestIp;
    }

    /**
     * Get local network IP address for development
     */
    private static function getLocalNetworkIp()
    {
        // Get the server's local IP address
        if (!empty($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] !== '127.0.0.1') {
            return $_SERVER['SERVER_ADDR'];
        }

        // Try to get hostname IP (most compatible)
        if (function_exists('gethostbyname')) {
            $hostname = gethostname();
            if ($hostname) {
                $ip = gethostbyname($hostname);
                if ($ip && $ip !== $hostname && $ip !== '127.0.0.1' && filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        // For Windows/XAMPP, try to get network adapter IP (simplified)
        if (PHP_OS_FAMILY === 'Windows') {
            // Try to use system command if available
            $output = @shell_exec('ipconfig 2>nul');
            if ($output && !empty(trim($output))) {
                // Extract all IPv4 addresses
                preg_match_all('/IPv4.*?:\s*(\d+\.\d+\.\d+\.\d+)/', $output, $matches);
                if (!empty($matches[1])) {
                    // Return the first non-localhost IP
                    foreach ($matches[1] as $ip) {
                        if ($ip !== '127.0.0.1' && filter_var($ip, FILTER_VALIDATE_IP)) {
                            return $ip;
                        }
                    }
                }
            }
        } else {
            // For Linux/Mac
            $output = shell_exec("hostname -I | awk '{print $1}'");
            if ($output && trim($output) !== '127.0.0.1') {
                $ip = trim($output);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return null;
    }

    /**
     * Get IP address info (country, city, etc.)
     */
    public static function getIpInfo($ip = null)
    {
        if (!$ip) {
            $ip = self::getRealIpAddress();
        }

        // Skip local IPs
        if (in_array($ip, ['127.0.0.1', '::1']) || 
            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return [
                'ip' => $ip,
                'country' => 'Local Development',
                'city' => 'localhost',
                'isp' => 'Local Network'
            ];
        }

        // You can integrate with IP geolocation services here
        // For now, return basic info
        return [
            'ip' => $ip,
            'country' => 'Unknown',
            'city' => 'Unknown',
            'isp' => 'Unknown'
        ];
    }

    /**
     * Check if IP is from local network
     */
    public static function isLocalIp($ip = null)
    {
        if (!$ip) {
            $ip = self::getRealIpAddress();
        }

        // Check for local addresses
        $localPatterns = [
            '127.0.0.1',
            '::1',
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16'
        ];

        foreach ($localPatterns as $pattern) {
            if (strpos($pattern, '/') !== false) {
                // CIDR notation - only works for IPv4
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    list($subnet, $mask) = explode('/', $pattern);
                    if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1)) == ip2long($subnet)) {
                        return true;
                    }
                }
            } else {
                // Direct match
                if ($ip === $pattern) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Format IP address for display (shorten long IPv6)
     */
    public static function formatIpForDisplay($ip = null)
    {
        if (!$ip) {
            $ip = self::getRealIpAddress();
        }

        // If it's IPv6 and too long, shorten it for display
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // Compress IPv6 address
            $compressed = inet_ntop(inet_pton($ip));
            
            // If still too long, truncate with ellipsis
            if (strlen($compressed) > 25) {
                return substr($compressed, 0, 22) . '...';
            }
            
            return $compressed;
        }

        return $ip;
    }

    /**
     * Get IP address type (IPv4, IPv6, Local)
     */
    public static function getIpType($ip = null)
    {
        if (!$ip) {
            $ip = self::getRealIpAddress();
        }

        if (self::isLocalIp($ip)) {
            return 'Local';
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return 'IPv4';
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return 'IPv6';
        }

        return 'Unknown';
    }
}
<?php
return [
    // Default radius in meters
    'radius_meters' => 50,

    // Primary store coordinates (example). Update to your real store location.
    'stores' => [
        [
            'id' => 1,
            'name' => 'Main Store',
            'lat' => 14.652857, // example latitude
            'lng' => 121.044955, // example longitude
        ],
    ],

    // Office Wi-Fi / network public IPs or CIDR ranges that are allowed.
    // Note: Browsers cannot expose SSID for privacy. Use your office's public IP
    // or CIDR ranges from which employees access the app. Example placeholders:
    'allowed_networks' => [
        '136.158.37.82',           // single IP
        // '198.51.100.0/24',       // CIDR range
    ],

    // If employees belong to stores, you can map by user ID later
];

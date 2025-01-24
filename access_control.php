<?php
// Function to get geolocation and VPN data using ip-api
function getGeolocationData($ip) {
    $apiUrl = "http://ip-api.com/json/{$ip}?fields=status,message,country,proxy";
    $response = @file_get_contents($apiUrl);
    return json_decode($response, true);
}

// Function to parse access rules from access.txt
function getAccessRules($filePath) {
    $rules = [
        'allow' => [],
        'block' => [],
        'only' => [],
    ];

    if (!file_exists($filePath)) {
        return $rules; // Return empty rules if file doesn't exist
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        [$type, $country] = explode(':', $line) + [null, null];
        $type = strtolower(trim($type));
        $country = trim($country);

        if (in_array($type, ['allow', 'block', 'only'], true) && $country) {
            $rules[$type][] = $country;
        }
    }

    return $rules;
}

// Get the client's IP address
$clientIp = $_SERVER['REMOTE_ADDR'];

// Fallback for IP detection if server is behind a proxy
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $clientIp = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
}

// Fetch geolocation and VPN data
$geoData = getGeolocationData($clientIp);

// Handle errors gracefully
if ($geoData['status'] !== 'success') {
    echo "<div style='color: orange; font-size: 18px;'>Error: Unable to determine your location. Access granted by default.</div>";
    return;
}

// Extract country and proxy information
$country = $geoData['country'] ?? 'Unknown';
$isVpn = $geoData['proxy'] ?? false;

// Load access rules
$accessRules = getAccessRules('access.txt');

// VPN blocking logic
if ($isVpn) {
    echo "<div style='color: red; font-size: 18px;'>Access Denied: VPNs are not allowed on this website.</div>";
    exit;
}

// "only" access logic
if (!empty($accessRules['only'])) {
    if (in_array($country, $accessRules['only'], true)) {
        echo "<div style='color: green; font-size: 18px;'>Access Granted: Welcome, User from {$country}.</div>";
        return;
    } else {
        echo "<div style='color: red; font-size: 18px;'>Access Denied: Only {$country} are allowed on this website.</div>";
        exit;
    }
}

// Block/allow logic
if (in_array($country, $accessRules['block'], true)) {
    echo "<div style='color: red; font-size: 18px;'>Access Denied: Visitors from {$country} are restricted from accessing this website.</div>";
    exit;
}

if (!empty($accessRules['allow']) && !in_array($country, $accessRules['allow'], true)) {
    echo "<div style='color: red; font-size: 18px;'>Access Denied: Visitors from {$country} are not in the allowed countries list.</div>";
    exit;
}

echo "<div style='color: green; font-size: 18px;'>Access Granted: Welcome, Visitor from {$country}.</div>";
?>

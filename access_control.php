<?php
// Function to get geolocation and VPN data using ip-api
function getGeolocationData($ip) {
    $apiUrl = "http://ip-api.com/json/{$ip}?fields=status,message,country,proxy";
    $response = @file_get_contents($apiUrl);
    return json_decode($response, true);
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

// Block logic with specific messages
if ($isVpn) {
    echo "<div style='color: red; font-size: 18px;'>Access Denied: VPNs are not allowed on this website.</div>";
    exit;
}

if ($country === 'India') {
    echo "<div style='color: red; font-size: 18px;'>Access Denied: Visitors from India are restricted from accessing this website.</div>";
    exit;
}

if ($country === 'Israel') {
    echo "<div style='color: red; font-size: 18px;'>Access Denied: Visitors from Israeli Occupied Areas are restricted from accessing this website.</div>";
    exit;
}

?>

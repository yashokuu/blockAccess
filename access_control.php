<?php
// Enable error reporting (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to get geolocation and VPN data
function getGeolocationData($ip) {
    $apiUrl = "http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,proxy";
    $response = @file_get_contents($apiUrl);
    
    if (!$response) {
        return ['status' => 'fail', 'message' => 'API error'];
    }

    return json_decode($response, true);
}

function checkAccess() {
    include "access_control.php"; // Run access checks
}

// Function to parse access rules
function getAccessRules($filePath) {
    $rules = ['allow' => [], 'block' => [], 'only' => []];

    if (!file_exists($filePath)) {
        return $rules;
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

// Get client IP
$clientIp = $_SERVER['REMOTE_ADDR'];
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $clientIp = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
}

// Fetch geolocation and VPN data
$geoData = getGeolocationData($clientIp);

// Handle API errors
if ($geoData['status'] !== 'success') {
    showBlockPage("Warning", "Unable to determine your location. Access granted by default.", "orange", "fa-exclamation-triangle", "");
}

// Extract country and VPN status
$country = $geoData['country'] ?? 'Unknown';
$countryCode = $geoData['countryCode'] ?? 'UN';
$isVpn = $geoData['proxy'] ?? false;
$flagUrl = "https://flagsapi.com/{$countryCode}/flat/64.png";

// Load access rules
$accessRules = getAccessRules(__DIR__ . '/access.txt');

// VPN blocking logic
if ($isVpn) {
    showBlockPage("Access Denied", "VPNs are not allowed on this website.", "red", "fa-ban", $flagUrl);
}

// "only" access logic
if (!empty($accessRules['only']) && !in_array($country, $accessRules['only'], true)) {
    showBlockPage("Access Denied", "Only visitors from selected countries are allowed.", "red", "fa-times-circle", $flagUrl);
}

// Block logic
if (in_array($country, $accessRules['block'], true)) {
    showBlockPage("Access Denied", "Visitors from {$country} are restricted.", "red", "fa-times-circle", $flagUrl);
}

// Allow logic
if (!empty($accessRules['allow']) && !in_array($country, $accessRules['allow'], true)) {
    showBlockPage("Access Denied", "Visitors from {$country} are not in the allowed list.", "red", "fa-times-circle", $flagUrl);
}

// ✅ If access is granted, continue loading the main page
return;

// Function to display block page and stop execution
function showBlockPage($title, $message, $color, $icon, $flagUrl) {
    echo "
    <html>
    <head>
        <title>$title</title>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
            body {
                font-family: 'Poppins', sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f4f4f4;
                margin: 0;
            }
            .container {
                text-align: center;
                background: #fff;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
                max-width: 400px;
                width: 90%;
                animation: fadeIn 0.6s ease-in-out;
            }
            h1 {
                color: $color;
                font-size: 24px;
            }
            p {
                color: #333;
                font-size: 18px;
            }
            .icon {
                font-size: 50px;
                color: $color;
                margin-bottom: 10px;
            }
            .flag {
                margin-top: 15px;
                width: 64px;
                height: auto;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <i class='fas $icon icon'></i>
            <h1>$title</h1>
            <p>$message</p>
            " . ($flagUrl ? "<img src='$flagUrl' alt='Flag' class='flag'>" : "") . "
        </div>
    </body>
    </html>";
    exit;
}
?>

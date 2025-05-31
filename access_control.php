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

// Function to parse access rules
function getAccessRules($filePath) {
    $rules = ['allow' => [], 'block' => [], 'only' => [], 'allow_vpn' => false];

    if (!file_exists($filePath)) {
        return $rules;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode(':', $line);
        if (count($parts) < 2) {
            continue; // Skip invalid lines
        }

        [$type, $value] = $parts;
        $type = strtolower(trim($type ?? ''));
        $value = trim($value ?? '');

        if ($type === 'allow_vpn') {
            $rules['allow_vpn'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        } elseif (in_array($type, ['allow', 'block', 'only'], true) && $value) {
            $rules[$type][] = $value;
        }
    }

    return $rules;
}

// Function to check access
function checkAccess($clientIp, $accessRules) {
    $geoData = getGeolocationData($clientIp);

    if ($geoData['status'] !== 'success') {
        return ["Access granted by default due to API error.", "orange", "fa-exclamation-triangle", ""];
    }

    $country = $geoData['country'] ?? 'Unknown';
    $countryCode = $geoData['countryCode'] ?? 'UN';
    $isVpn = $geoData['proxy'] ?? false;
    $flagUrl = "https://flagsapi.com/{$countryCode}/flat/64.png";

    if ($isVpn && !$accessRules['allow_vpn']) {
        return ["VPNs are not allowed on this website.", "red", "fa-ban", $flagUrl];
    }

    if (!empty($accessRules['only']) && !in_array($country, $accessRules['only'], true)) {
        return ["Only visitors from selected countries are allowed.", "red", "fa-times-circle", $flagUrl];
    }

    if (in_array($country, $accessRules['block'], true)) {
        return ["Visitors from {$country} are restricted.", "red", "fa-times-circle", $flagUrl];
    }

    if (!empty($accessRules['allow']) && !in_array($country, $accessRules['allow'], true)) {
        return ["Visitors from {$country} are not in the allowed list.", "red", "fa-times-circle", $flagUrl];
    }

    return ["Access granted.", "green", "fa-check-circle", $flagUrl];
}

// Function to display block page
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
                background: linear-gradient(135deg, #f4f4f4, #e0e0e0);
                margin: 0;
            }
            .container {
                text-align: center;
                background: #fff;
                padding: 40px;
                border-radius: 15px;
                box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3);
                max-width: 450px;
                width: 90%;
                animation: fadeIn 0.6s ease-in-out;
            }
            h1 {
                color: $color;
                font-size: 28px;
                margin-bottom: 15px;
            }
            p {
                color: #555;
                font-size: 20px;
                margin-bottom: 20px;
            }
            .icon {
                font-size: 60px;
                color: $color;
                margin-bottom: 20px;
            }
            .flag {
                margin-top: 20px;
                width: 80px;
                height: auto;
                border-radius: 5px;
                box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-20px); }
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
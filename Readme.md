# Geolocation and VPN Detection using IP-API

This PHP project detects the client's geolocation and VPN usage using the `ip-api` service and applies access control logic based on the detected data.

---

## Features
- Detects the client's country based on their IP address.
- Identifies if the client is using a VPN or proxy.
- Implements access restriction logic based on country and VPN usage.
- Provides user-friendly error messages in case of failures.

---

## Requirements
- PHP-enabled web server.
- Internet access to query the IP-API service.

---

## Installation
1. Clone or download the repository to your PHP server.
2. Place the PHP file in your server's web directory.
3. Add this code to the file that u want to restrict access
```php
<?php include "access_control.php"?>
```
3. Ensure the server can make outbound HTTP requests to `http://ip-api.com`.

---

## How It Works
1. **IP Detection**: The script uses `$_SERVER['REMOTE_ADDR']` to get the client's IP address. If the server is behind a proxy, it checks `$_SERVER['HTTP_X_FORWARDED_FOR']` for the forwarded IP.
2. **API Query**: The IP is sent to the IP-API service to fetch geolocation and VPN data.
3. **Error Handling**: If the API call fails or returns an error, access is granted by default with a warning message.
4. **Access Logic**:
   - Blocks clients using VPNs.
   - Restricts access for users from specific countries (India and Israel).
5. **Response**: Displays a message based on the access decision.

---

## Customization
- **Restricted Countries**:
  Modify the `if ($country === 'India')` and `if ($country === 'Israel')` blocks to add or remove restrictions for other countries.
- **VPN Policy**:
  Adjust the `if ($isVpn)` block to change the behavior for VPN users.

---

## Example Output
### Success
```html
<div>Your access is granted. </div>
```
### Restricted Access (VPN)
```html
<div style='color: red; font-size: 18px;'>Access Denied: VPNs are not allowed on this website.</div>
```
### Restricted Access (Country)
```html
<div style='color: red; font-size: 18px;'>Access Denied: Visitors from India are restricted from accessing this website.</div>
```
### Error
```html
<div style='color: orange; font-size: 18px;'>Error: Unable to determine your location. Access granted by default.</div>
```

---

## Notes
- Ensure compliance with privacy and legal regulations when implementing geolocation-based restrictions.
- The script depends on the availability of the IP-API service. Consider implementing caching to reduce API usage.

---

## License
This project is licensed under the MIT License. See the `LICENSE` file for details.

---

## Acknowledgments
- [IP-API](http://ip-api.com) for providing the geolocation and VPN detection service.

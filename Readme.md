# Geolocation and VPN Detection using IP-API

This PHP project detects the client's geolocation and VPN usage using the `ip-api` service and applies access control logic based on the detected data.

---

## Features
- Detects the client's country based on their IP address.
- Identifies if the client is using a VPN or proxy.
- Implements flexible access control logic using a configuration file (`access.txt`).
- Provides user-friendly error messages in case of failures.

---

## Requirements
- PHP-enabled web server.
- Internet access to query the IP-API service.
- A configuration file named `access.txt` for access rules.

---

## Installation
1. Clone or download the repository to your PHP server.
2. Place the PHP file (`access_control.php`) in your server's web directory.
3. Create an `access.txt` file in the same directory with access rules. Example:
   ```
   allow:USA
   allow:Canada
   block:India
   block:Israel
   superuser:Malaysia
   ```
4. Add this code to the file where you want to restrict access:
   ```php
   <?php include "access_control.php"; ?>
   ```
5. Ensure the server can make outbound HTTP requests to `http://ip-api.com`.

---

## How It Works
1. **IP Detection**: The script uses `$_SERVER['REMOTE_ADDR']` to get the client's IP address. If the server is behind a proxy, it checks `$_SERVER['HTTP_X_FORWARDED_FOR']` for the forwarded IP.
2. **API Query**: The IP is sent to the IP-API service to fetch geolocation and VPN data.
3. **Error Handling**: If the API call fails or returns an error, access is granted by default with a warning message.
4. **Access Logic**:
   - Reads `access.txt` to determine rules.
   - Grants access to countries marked as `allow`.
   - Denies access to countries marked as `block`.
   - Overrides other rules for countries marked as `superuser`.
5. **Response**: Displays a message based on the access decision.

---

## Customization
- **Access Rules**:
  Modify the `access.txt` file to add, remove, or update country rules.
- **VPN Policy**:
  Adjust the `if ($isVpn)` block in `access_control.php` to change the behavior for VPN users.

---

## Example `access.txt`
```plaintext
# This file contains access rules for the website:
# - 'allow' specifies countries that are explicitly allowed to access the site.
# - 'block' specifies countries that are explicitly denied access to the site.
# - 'superuser' specifies countries that are granted special access, overriding other restrictions.

allow:USA
allow:Canada
block:India
block:Israel
superuser:Malaysia
```

---

## Example Output
### Success
```html
<div>Your access is granted.</div>
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

## Instructions for Use
1. Add the `access_control.php` script to your PHP project.
2. Create and configure the `access.txt` file with your desired rules.
3. Include the `access_control.php` script in any PHP file where you want to apply access restrictions:
   ```php
   <?php include "access_control.php"; ?>
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

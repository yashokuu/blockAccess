<a id="styles"></a>  
<img src="https://readme-typing-svg.herokuapp.com?font=Lexend+Giga&size=25&pause=1000&color=CCA9DD&vCenter=true&width=435&height=25&lines=blockAccess (BA)" width="450"/>
---

This PHP project detects the client's geolocation and VPN usage using the `ip-api` service and applies access control logic based on the detected data.

---

<a id="styles"></a>  
<img src="https://readme-typing-svg.herokuapp.com?font=Lexend+Giga&size=25&pause=1000&color=CCA9DD&vCenter=true&width=435&height=25&lines=Features" width="450"/>
---
- Detects the client's country based on their IP address.
- Identifies if the client is using a VPN or proxy.
- Implements flexible access control logic using a configuration file (`access.txt`).
- Provides user-friendly error messages in case of failures.

---

<a id="styles"></a>  
<img src="https://readme-typing-svg.herokuapp.com?font=Lexend+Giga&size=25&pause=1000&color=CCA9DD&vCenter=true&width=435&height=25&lines=Requirements" width="450"/>
---
- PHP-enabled web server.
- Internet access to query the IP-API service.
- A configuration file named `access.txt` for access rules.

---

<a id="styles"></a>  
<img src="https://readme-typing-svg.herokuapp.com?font=Lexend+Giga&size=25&pause=1000&color=CCA9DD&vCenter=true&width=435&height=25&lines=Installation" width="450"/>
---
1. Clone or download the repository to your PHP server.
2. Place the PHP file (`access_control.php`) in your server's web directory.
3. Create an `access.txt` file in the same directory with access rules. Example:
   ```
   allow:USA
   allow:Canada
   block:India
   block:Israel
   only:Malaysia
   ```
4. Add this code to the file where you want to restrict access:
   ```php
   <?php include "access_control.php"; ?>
   ```
5. Ensure the server can make outbound HTTP requests to `http://ip-api.com`.

---

<a id="styles"></a>  
<img src="https://readme-typing-svg.herokuapp.com?font=Lexend+Giga&size=25&pause=1000&color=CCA9DD&vCenter=true&width=435&height=25&lines=How it Works" width="450"/>
---
1. **IP Detection**: The script uses `$_SERVER['REMOTE_ADDR']` to get the client's IP address. If the server is behind a proxy, it checks `$_SERVER['HTTP_X_FORWARDED_FOR']` for the forwarded IP.
2. **API Query**: The IP is sent to the IP-API service to fetch geolocation and VPN data.
3. **Error Handling**: If the API call fails or returns an error, access is granted by default with a warning message.
4. **Access Logic**:
   - Reads `access.txt` to determine rules.
   - Grants access to countries marked as `allow`.
   - Denies access to countries marked as `block`.
   - Overrides other rules for countries marked as `only`.
5. **Response**: Displays a message based on the access decision.

---

<a id="styles"></a>  
<img src="https://readme-typing-svg.herokuapp.com?font=Lexend+Giga&size=25&pause=1000&color=CCA9DD&vCenter=true&width=435&height=25&lines=Customization" width="450"/>
---
- **Access Rules**:
  Modify the `access.txt` file to add, remove, or update country rules.
- **VPN Policy**:
  Adjust the `if ($isVpn)` block in `access_control.php` to change the behavior for VPN users.

---

<a id="styles"></a>  
<img src="https://readme-typing-svg.herokuapp.com?font=Lexend+Giga&size=25&pause=1000&color=CCA9DD&vCenter=true&width=435&height=25&lines=Example 'access.txt'" width="450"/>
---
```plaintext
# This file contains access rules for the website:
# - 'allow' specifies countries that are explicitly allowed to access the site.
# - 'block' specifies countries that are explicitly denied access to the site.
# - 'only' specifies countries that are granted special access, overriding other restrictions.

allow:USA
allow:Canada
block:India
block:Israel
only:Malaysia
```

---

<a id="styles"></a>  
<img src="https://readme-typing-svg.herokuapp.com?font=Lexend+Giga&size=25&pause=1000&color=CCA9DD&vCenter=true&width=435&height=25&lines=Instructions for use" width="450"/>
---
1. Add the `access_control.php` script to your PHP project.
2. Create and configure the `access.txt` file with your desired rules.
3. Include the `access_control.php` script in any PHP file where you want to apply access restrictions:
   ```php
   <?php include "access_control.php"; ?>
   ```

---

<a id="styles"></a>  
<img src="https://readme-typing-svg.herokuapp.com?font=Lexend+Giga&size=25&pause=1000&color=CCA9DD&vCenter=true&width=435&height=25&lines=Notes" width="450"/>
---
- Ensure compliance with privacy and legal regulations when implementing geolocation-based restrictions.
- The script depends on the availability of the IP-API service. Consider implementing caching to reduce API usage.

---

<a id="styles"></a>  
<img src="https://readme-typing-svg.herokuapp.com?font=Lexend+Giga&size=25&pause=1000&color=CCA9DD&vCenter=true&width=435&height=25&lines=License" width="450"/>
---
This project is licensed under the MIT License. See the `LICENSE` file for details.

---

<a id="styles"></a>  
<img src="https://readme-typing-svg.herokuapp.com?font=Lexend+Giga&size=25&pause=1000&color=CCA9DD&vCenter=true&width=435&height=25&lines=Acknowledgement" width="450"/>
---
- [IP-API](http://ip-api.com) for providing the geolocation and VPN detection service.
- [Flag API](https://flagsapi.com) for providing additional country flag data.
---



<a id="styles"></a>  
<img src="https://readme-typing-svg.herokuapp.com?font=Lexend+Giga&size=25&pause=1000&color=CCA9DD&vCenter=true&width=435&height=25&lines=Version : 3_Beta" width="450"/>

# OnSend for WooCommerce (Community Fork)

[![License](https://img.shields.io/badge/license-GPL--3.0-blue.svg)](LICENSE)

> **⚠️ LEGAL NOTICE:** This is an **independent community-maintained fork**. We are **NOT** affiliated with, endorsed by, or associated with [onsend.io](https://onsend.io) or its owners. 

This repository provides maintenance and critical bug fixes for the OnSend messaging plugin. This fork is intended for users who require compatibility with modern WooCommerce (10.x+) and WordPress (6.x+) environments.

---

## 🛑 Disclaimer

### Non-Affiliation
- This project is a "fork" of the software originally distributed by OnSend. 
- This repository is **not** the official source of the plugin.
- Any third-party brand names or trademarks mentioned are the property of their respective owners and are used here strictly for compatibility description.

### No Warranty & Limitation of Liability (GPLv3)
This software is provided **"AS IS"**, without warranty of any kind, express or implied. 
- **Volunteer Effort:** This is a free, non-commercial project provided to the community without any payment or compensation.
- **No Responsibility:** The maintainers of this fork are not responsible for any issues, data loss, account disruptions, or damages caused by the use of this plugin or any third-party messaging services it connects to.
- **Use at Your Own Risk:** By installing this plugin, you acknowledge that you are doing so at your own risk and are responsible for complying with the Terms of Service of any external APIs used.

---

## 🚀 Improvements in this Fork (v1.1.0+)
- **Duplicate Notification Guard:** Fixed a critical issue where multiple messages were sent during order status transitions (specifically the `processing` status).
- **Idempotency Logic:** Implemented a "check-and-set" mechanism using order metadata to ensure a specific notification is only triggered once per status change.
- **Modern WC Compatibility:** Updated hooks and background processing logic for WooCommerce 10.5.3+ and WordPress 6.9.1+.

## 📦 Installation & Updates
Since the original plugin does not support automatic updates:
1. Download the latest `.zip` from the [Releases](https://github.com/yourusername/onsend-woocommerce-plugin-fork/releases) page.
2. In your WordPress admin, go to **Plugins > Add New > Upload Plugin**.
3. Upload the zip file. If prompted, select **Replace current with uploaded**.

---
*All trademarks, service marks, and company names are the property of their respective owners.*
=== OnSend ===
Contributors:      onsend, ShahmiMajid
Tags:              onsend, onpay, messaging, woocommerce, notification, message
Requires at least: 4.6
Tested up to:      6.9.1
Stable tag:        1.2.0
Requires PHP:      8.0
License:           GPLv3 or later
License URI:       http://www.gnu.org/licenses/gpl-3.0.html

Send messaging notifications to your customers through OnSend. This is a community-maintained fork.

== Description ==

This is an independent community-maintained fork of the OnSend plugin. It provides critical bug fixes and maintenance for modern WordPress and WooCommerce environments.

OnSend is a messaging API provider that enables you to send notifications to your customers when a new WooCommerce order is placed or the WooCommerce order status changes.

**⚠️ LEGAL NOTICE:** This project is **NOT** affiliated with, endorsed by, or associated with onsend.io or its owners. This software is provided **"AS IS"** without warranty of any kind. Use at your own risk.

== Installation ==

1. Download the latest plugin `.zip` from the GitHub Releases page of this fork.
2. Log in to your WordPress admin.
3. Go to **Plugins > Add New > Upload Plugin**.
4. Upload the zip file and click **Install Now**.
5. Activate the plugin and navigate to the "Settings" page to configure your API details.

== Changelog ==

= 1.2.0 - 2026-03-06 =
- **Fixed:** Atomic post-meta lock to prevent duplicate WooCommerce WhatsApp notifications

= 1.1.0 - 2026-03-06 =
- **Community Fork Release** by ShahmiMajid.
- **Fixed:** Duplicate notifications sent during order status transitions (Idempotency Guard).
- **Improved:** Compatibility with WooCommerce 10.5.3 and WordPress 6.9.1.
- **UI/Text:** Updated terminology to generic messaging to avoid trademark confusion.

= 1.0.0 - 2023-08-16 =
- Initial release of the plugin by the original author.

== Contributors to this Fork ==

- ShahmiMajid (Maintenance, Bug Fixes, and Modern Compatibility)
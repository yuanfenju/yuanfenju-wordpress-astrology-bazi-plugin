=== Chinese Astrology & Divination Toolkit – Bazi & Ziwei Tools ===
Contributors: yuanfenju
Tags: chinese astrology, astrology, bazi, ziwei, feng shui
Requires at least: 5.2
Tested up to: 6.9
Stable tag: 2.0.2
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A modular WordPress plugin that provides Chinese metaphysics calculation tools for astrology and divination systems.

== Description ==

A powerful WordPress plugin that turns your website into a Chinese astrology calculator, allowing users to instantly generate Bazi (Four Pillars) charts and Ziwei Dou Shu readings online.

Perfect for astrology websites, bloggers, and SaaS platforms, this plugin turns WordPress into a full-featured Chinese metaphysics calculation system.

Users can embed astrology tools anywhere using simple shortcodes — no coding required.

It supports both Sandbox Mode (offline demo data) and Live API Mode (real-time calculations via Yuanfenju API).

Core features include Bazi analysis, Ziwei astrology charts, Qimen Dunjia, almanac tools, and daily analysis tools.

This plugin is designed for content creators and developers who need a structured, scalable astrology engine inside WordPress.

### Features
* Modular architecture for enabling required calculation modules only
* Sandbox Mode for local testing without external API access
* Live API Mode for real calculation results
* Multilingual output support (Simplified Chinese and Traditional Chinese)
* Shortcode support for embedding tools anywhere in WordPress
* Developer-friendly structure for extensibility

### Third-Party Service & Privacy Disclosure

When Live Mode is enabled, this plugin communicates with an external API service (api.yuanfenju.com) over HTTPS.

* **Data Sent:** User-submitted inputs such as name, gender, and birth date
* **Purpose:** Used only for astrology and numerology calculations
* **Storage:** The API provider states that no permanent storage of user data is performed.
* **Retention:** No long-term retention of personal data by this plugin

**Privacy Summary:**
- No permanent storage of user data by this plugin
- Secure HTTPS transmission only
- No data sharing or resale
- This plugin acts as a data processor and does not act as a data controller.
- The external API provider (Yuanfenju) acts as the data controller for data processed via Live Mode.

By using Live Mode, you agree to the following:

* Terms of Service:
  - https://doc.yuanfenju.com/other/agreement.html
  - https://doc.yuanfenju.com/other/agreement.tw.html

* Privacy Policy:
  - https://doc.yuanfenju.com/other/privacy.html
  - https://doc.yuanfenju.com/other/privacy.tw.html

### Mode Behavior

This plugin operates in two modes:

**Sandbox Mode (Default)**
- Fully offline
- Uses built-in static sample data
- No API key required
- No external network requests

**Live Mode**
- Requires a valid API key
- Sends requests to external API service (api.yuanfenju.com)
- Used for real-time astrology calculations

Users can use the plugin entirely in Sandbox Mode without any external dependency.
This plugin does not store or retain any user-submitted data locally.
Sandbox Mode is intended for development and demonstration purposes only.

== Installation ==

1. Upload the `yuanfenju-astrology-toolkit` folder to `/wp-content/plugins/`
2. Activate the plugin in WordPress admin panel
3. Navigate to the plugin settings page in the WordPress admin dashboard
4. Enable Sandbox Mode for testing or Live Mode for production

== Frequently Asked Questions ==

= Do I need an API key? =
No. Sandbox Mode works without any API key.

= Is user data stored? =
No. All data is processed in real-time and not stored by the plugin.

= How do I get a Live API Key? =
You can register for an API key via the Yuanfenju service portal.

== Screenshots ==

1. Settings Dashboard
2. Module Management Interface
3. Frontend Calculator Forms

== Changelog ==

= 2.0.2 =
* Initial release
* Added Sandbox Mode
* Added Live API Mode

== Upgrade Notice ==

= 2.0.2 =
This is the initial release of the Chinese Astrology & Divination Toolkit. No upgrade actions are required at this time.

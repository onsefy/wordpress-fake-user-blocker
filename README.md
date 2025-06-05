# OnSefy Fraud User Blocker WordPress Plugin

Block fake signups and prevent fraud with OnSefy’s advanced fraud detection API.

## Description

OnSefy Wordpress Fraud User Blocker integrates the OnSefy API into your WordPress registration process to detect and block fraudulent signups based on a configurable risk score threshold.

- Supports Free and Paid OnSefy API plans
- Blocks users with risk scores above threshold on registration
- Admin settings to configure API keys, service ID, plan type, and risk score threshold
- Admin notice on blocked registrations
- Simple and lightweight plugin

Here’s a cleaned-up and more complete version of your **Installation** section with a standard download process added:

---

## Installation

### 📦 Download

You can install the plugin in two ways:

#### Option 1: Manual Installation (ZIP File)

1. [Download the plugin ZIP file](https://github.com/onsefy/wordpress-fake-user-blocker/releases/download/v1.0.0/onsefy-fraud-user-blocker.zip) to your computer.
2. In your WordPress dashboard, go to **Plugins > Add New > Upload Plugin**.
3. Click **Choose File**, select the downloaded `.zip` file, then click **Install Now**.
4. After installation, click **Activate Plugin**.

#### Option 2: Upload via FTP

1. Extract the downloaded ZIP file.
2. Upload the entire `onsefy-fraud-user-blocker` folder to the `/wp-content/plugins/` directory using FTP or your hosting file manager.


### ⚙️ Configuration

1. Activate the plugin from the **Plugins** menu in WordPress.
2. Obtain your **API Key**, **Service ID** from your OnSefy dashboard at https://onsefy.com/.
3. Go to **Settings > OnSefy Fraud User Blocker**.
4. Enter your **API Key**, **Service ID**, choose your **Plan Type** (Free or Paid), and set your desired **Risk Threshold**.
5. Click **Save Changes**.
6. Test your registration form to ensure the plugin is working correctly.

## Usage

- The plugin runs automatically on new user registrations.
- If the user’s risk score from OnSefy exceeds the configured threshold, the user registration is blocked.
- Blocked users are immediately deleted and an admin notice is displayed.

## Frequently Asked Questions

**Q: Can I block users on other actions like comments or login?**  
A: Currently, the plugin only blocks on user registration.

**Q: How do I find my API key and service ID?**  
A: Obtain them from your OnSefy dashboard at https://onsefy.com/.

**Q: Can I customize the risk score threshold?**  
A: Yes, via the plugin settings page.

## Changelog

### 1.0.0
- Initial release with registration fraud detection and blocking
- Admin settings page
- Admin notices for blocked users

## License

GPLv2 or later

---

## Support

Visit [https://onsefy.com](https://onsefy.com) for support and documentation.

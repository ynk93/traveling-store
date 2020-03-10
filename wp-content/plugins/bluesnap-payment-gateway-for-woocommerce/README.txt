=== Plugin Name ===
Contributors: bluesnap, harmz
Donate link: https://saucal.com
Tags: woocommerce, payment gateway, bluesnap
Requires at least: 4.7
Tested up to: 5.2.3
Requires PHP: 7.0
Requires WooCommerce: 3.0
Stable tag: 1.3.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Accept cards and Apple Pay, along with support for WooCommerce Subscriptions and Pre-orders on a global payments gateway.

== Description ==

#Why use the BlueSnap Payment Gateway for WooCommerce
 * Accept all major debit and credit cards as well as Apple Pay 
 * Simplify your PCI compliance using our built-in Hosted Payment Fields. Your shoppers never leave your site while you maintain easy PCI compliance.
 * Supports Strong Customer Authentication (SCA) with 3D Secure
 * Identify and prevent fraud with built-in fraud protection from Kount
 * Sell in 100 currencies 
 * Support for WooCommerce pre-orders
 * Support recurring payments via WooCommerce Subscriptions

#A secure and frictionless checkout flow 
The BlueSnap Payment Gateway for WooCommerce uses our Hosted Payment Fields to provide you with a seamless, PCI-compliant checkout experience that works within any browser or device.  Our Hosted Payment Fields silently collect your shopper’s sensitive payment data on BlueSnap servers without interrupting the checkout flow.

#Sell and settle in multiple currencies
A simple way to help your shoppers complete their purchase is to offer your products in the currency of your shoppers. The BlueSnap Payment Gateway allows your shoppers to checkout in 100 different currencies and, as an added benefit, using the BlueSnap Payment Gateway gets you access to our connections to 30 global banks. When your shopper completes their purchase, BlueSnap paves the most efficient path to payment success by routing the transaction to the most appropriate local bank for your shopper, minimizing decline rates and maximizing revenue gains. 

Once the sale is complete and you need to get paid, BlueSnap works with you by offering the option to get money into your account in one of our 17 like-for-like payout currencies. 

#Fraud Protection and 3D Secuure
The BlueSnap Payment Gateway offers Kount Fraud protection right from the plugin to best optimize the checkout flow. We also provide the option to select advanced fraud options if you want to customize your level of fraud screening. 

In addition, as you sell to shoppers around the world, you’ll likely run into a location where you are required to support a 3D Secure checkout experience.  The BlueSnap Payment Gateway has built-in support for 3DS so you aren't out of compliance in the regions where this is mandatory.

#Full support for Subscriptions and Pre-Orders
The BlueSnap Payment Gateway provides support for **WooCommerce Subscriptions**, offering support for all of subscription features, includig payment date changes, subscription date changes, and more. The gateway also fully supports **WooCommerce Pre-Orders**, so you can take customer’s payment information upfront and then automatically charge their payment method once the pre-order is released.


== Installation ==

For the most recent version of these instructions, refer to https://support.bluesnap.com/docs/woocommerce


#Requirements 
##Recommended Versions 
We recommend that you use the following versions when using the BlueSnap plugin for WooCommerce. The plugin may work when using older versions of PHP and MySQL as well; however, the following versions have been tested to ensure compatibility. 
* PHP: 5.6 or later 
* MySQL: 5.6 or later 
* WordPress: 4.5.3 or later 
* WooCommerce: 3.0 or later 
* WooCommerce Pre-Orders: 1.4.4 (minimum version supported) 
* WooCommerce Subscriptions: 2.0.8 (minimum version supported) 
 

##Software 
This guide assumes that you have: 
* A working WordPress platform 
WooCommerce is a WordPress plugin that is installed on top of the WordPress platform. If you don't yet have a working WordPress installation, you may want to contact your website hosting provider, as many of them supply a quick-install process for WordPress. 

* WooCommerce software installed and uploaded to your server. 
If you need the plugin, go to: https://wordpress.org/plugins/woocommerce/ or to http://www.woothemes.com/woocommerce/ to download the WooCommerce plugin. 

* If you want to use the Pre-Orders or Subscription functionalities, make sure that the respective plugins are also installed in your WordPress website: 
  * Pre-Orders:  http://www.woothemes.com/products/woocommerce-pre-orders/ 
  *  Subscriptions:  http://www.woothemes.com/products/woocommerce-subscriptions/ 


##PCI compliance 
A PCI compliance of SAQ-A is required. 


#Setup Steps 
##Step 1: Configure your BlueSnap account settings 
Before you install the BlueSnap extension, complete these steps in your BlueSnap account: 
1. Set up your BlueSnap API Credentials(https://developers.bluesnap.com/v8976-Basics/docs/api-credentials). Make note of your API username and password; you need them in later steps. 

2. Define the authorized IP address for your server. 

3. Configure your payout settings (https://support.bluesnap.com/docs/payout-method). 


##Step 2: Install the plugin 
Install the BlueSnap Payment Gateway plugin, as follows: 
1. In WordPress, click **Plugins > Add New** in the left menu. 

2. Search for `BlueSnap` in the search box in the top-right side. 

3. Click the **BlueSnap Payment Gateway for WooCommerce** plugin and install it. 

4. Click **Plugins > Installed Plugins** in the left menu. 

5. In the installed plugin screen, **activate** the following plugins, in this order:
   * WooCommerce 
   * WooCommerce Subscriptions (optional) 
   * WooCommerce Pre-Orders (optional) 
   * BlueSnap Payment Gateway for WooCommerce 

**Important**
If these are not activated in the specified order, the installation will not complete properly. 


##Step 3: Set the Default Currency 
Configure the default currency settings for WooCommerce by completing the following steps: 
1. Go to **WooCommerce > Settings > General**. 

2. Scroll down to **Currency Options** and set the values as necessary.

3. Click Save Changes. 


##Step 4: Configure the plugin 
Configure the BlueSnap plugin using the following steps: 
1. Click the **Settings** link below the BlueSnap plugin. The BlueSnap page opens. 

2. Configure the following settings. 

>**Note**: You can find your BlueSnap information (API credentials, Merchant ID, and more) for the following settings in your BlueSnap Merchant Console in **Settings > API Settings**. 

  * **Enable/Disable** &mdash; Select Enable BlueSnap. This means that BlueSnap appears as a payment option during checkout. 
  * **Test mode** &mdash; Select Enable Test Mode to use your BlueSnap Sandbox account, select the Enable Test Mode option. Leave the option cleared to use your BlueSnap Production account. 
  * **IPN configuration** &mdash; Copy the URL from this section and use it for the IPN Setup section below. 
  * **Title** &mdash; By default, this is Credit/Debit Cards. This label is presented to the shopper when they choose a payment option during checkout. 
  * **Description** &mdash; By default, this is Pay using your Credit/Debit Card. This describes the payment method during checkout. 
  * **API Username and API Password** &mdash; Enter your API Username and Password for your BlueSnap account. Use your sandbox credentials if you chose Enable Test Mode above. Use your production credentials if you did not chose Enable Test Mode above. 
  * **Merchant ID** &mdash; Enter your Merchant ID number from your BlueSnap merchant account. <br /> **Note**: Use the Merchant ID from you sandbox or production environment, as applicable. They are different. 
  * **Soft Descriptor** &mdash; Enter a string, no more than 20 characters in length. This descriptor appears on the shopper's billing statement to help them identify the purchase. You should use the same soft descriptor set in your BlueSnap Console. 
  * **3D Secure** &mdash; If you want to offer 3-D Secure, contact BlueSnap Merchant Support and ask for 3-D Secure to be enabled for your account. After that is done, you can select this option to activate 3-D Secure. For more information on 3-D Secure, refer to our 3-D Secure Guide (https://support.bluesnap.com/docs/3d-secure).  
  * **Saved Cards** &mdash; Select this if you want to give logged-in shoppers the option to store their credit card details for future purchases. They can manage their information from their My Account area. 
  * **BlueSnap currency converter** &mdash; BlueSnap works with many currencies (see a complete list at https://support.bluesnap.com/docs/currencies). The BlueSnap plugin for WooCommerce includes a built-in currency converter that you must configure in order to enable successful purchasing via BlueSnap. <br />Select this option to use the converter. 
  * **Select the currencies to display in your shop** &mdash; Select all the currencies your WooCommerce store supports. 
  * **Apple Pay Wallet** &mdash; If you want to offer Apple Pay as a payment method for your shoppers, contact BlueSnap Merchant Support (https://bluesnap.zendesk.com/hc/en-us/requests/new?ticket_form_id=360000127087) and ask for Apple Pay to be enabled for your account. After that is done, you can select this option to allow shoppers to pay with Apple Pay. 
  * **Logging** &mdash; Select the Log debug messages option to have communications between WooCommerce and BlueSnap recorded in the process log files. We recommend using this option during the development of your site or if you are experiencing any problems. <br />To access process logs for the BlueSnap plugin, go to **WooCommerce > Status** and click the **Logs** tab. 

3. Click Save Changes. 


##Step 5: Secure checkout 
Ensure that you are using secure checkout by completing the following steps. 
1. Go to **WordPress > Settings > General**. 

2. In the following URL fields, make sure that the URL begins with `https://`: 
  * **WordPress Address (URL)**
  * **Site Address (URL)** 


##Step 6. IPN Setup 
Instant Payment Notifications (IPNs) are webhooks that trigger an HTTP POST message to your WooCommerce account when an important event occurs. Follow the steps below to set up IPNs. 
1. Log in to your BlueSnap account and go to **Settings > General Settings**. 

2. In the **Notifications** section, select **Receive Instant Payment Notifications**. 

3. Update the **IPN URL(s)** field. The format of the URL should follow this pattern: `https://www.yourdomain.com/?wc-api=bluesnap`

4. Click Submit. 

For more information on IPNs, refer to our IPN documentation (https://support.bluesnap.com/docs/about-ipns). 


##Step 7: Crontab Setup 
We recommend that you add a line to your crontab. The crontab is an application that runs in the server operating the WordPress application, and is in charge of periodic actions. It ensures that subscriptions continue to charge on time even if your WooCommerce store has no traffic, stores automatic renewals, and handles pre-orders. 
The crontab file is available to you in most UNIX/Linux based machines, and often can be found in `/var/spool/cron`. If you are not sure where your crontab file is, reach out to your IT team or hosting provider for more details. 
You should add the following line to your crontab file: 
`*/15 * * * * {wget path} -q -O – {Web domain of your WooCommerce Store}/ wp-cron.php?doing_wp_cron` 
For example: `*/15 * * * * /usr/bin/wget -q -O - http://shoppingcarts.bluesnap.com/wordpress/wp-cron.php?doing_wp_cron `

If you have multiple WooCommerce Stores running on the same server, you should add this line for each one of them. 

**Note**:  `*/15` makes the crontab run every 15 minutes. You can use this to change the cron frequency. 

For additional help, contact BlueSnap Merchant Support (https://bluesnap.zendesk.com/hc/en-us/requests/new?ticket_form_id=360000127087). 
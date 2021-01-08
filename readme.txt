=== Dynamic Shortcode ===
Contributors: FanaticPythoner
Donate link: https://www.paypal.com/donate?hosted_button_id=9PG7333GR22ZA
Tags: shortcode, ajax, async, dynamic, dynamic shortcode, pagespeed
Requires at least: 5.4
Tested up to: 5.6
Stable tag: Dynamic Shortcode
Requires PHP: 7.4+
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Make any shortcode asynchronous - Eliminate render-blocking shortcodes, improve user experience and Google PageSpeed Insights.

== Description ==

Dynamic Shortcode is a <b>100% FREE</b> plugin that allows users to make any shortcode asynchronous in <b>one simple step</b>, meaning it won't block the Wordpress page render at all. Dynamic Shortcode has a simple and user friendly interface, making it extremely easy to use.

<br>

Dynamic Shortcode can either replace every occurence of a given shortcode without your intervention (currently in beta, the feature is called <i><b>Dynamic Replace</b></i>), or you can change every shortcode yourself in one simple step:

<br>

- Find an occurence of your shortcode, and replace your shortcode's name with <strong>wp_dynamic</strong>&nbsp;and add a parameter named&nbsp;<strong>shortcode</strong>&nbsp;with your shortcode's name as a value. For example:<br><br><strong>[myShortcode]<br></strong><br>becomes<br><br><strong>[wp_dynamic shortcode=''myShortcode'']<br></strong><br> 
<br>


But wait! That's not all: You can even <b>add custom placeholders for each individual shortcode</b>. Placeholders are displayed when Dynamic Shortcode is loading a shortcode asynchronously.

<br>

Dynamic Shortcode is packed with tons of other useful features.

<br>

For each individual shortcode on your site, here's everything Dynamic Shortcode can do for you:

- Make any already existing shortcode asynchronous automatically or in a single step


- Add custom placeholders for when your Dynamic Shortcode is loading your shortcode


- Run a validation function before executing your shortcode when making the asynchronous call


- Choose which GET parameters to ignore when doing the asynchronous call


- Choose which POST parameters to ignore when doing the asynchronous call


- Choose which shortcode attribute to ignore when doing the asynchronous call




== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/dynamic-shortcode-ajax` directory, or install the plugin through the WordPress plugins screen directly.


2. Activate the plugin through the 'Plugins' screen in WordPress


3. Use the <b>Shortcodes</b> admin menu to configure and use the plugin

== Frequently Asked Questions ==

= Where is the menu located? =

Go in your wp-admin page. Look at the admin menu on your left, you should see a tab "Shortcodes" with the Dynamic Shortcode logo.

= If I use the Dynamic Replace setting, will it break my site? =

Dynamic Replace will not permanently change your html pages: it works by replacing the shortcodes dynamically before each page load. Thus, it will not overwrite your html pages in any way.

= Does Dynamic Shortcode sanitize, validate and/or escape my data? =

Dynamic Shortcode act as a black box proxy: It takes the very state of the request while it tries to call the original shortcode, saves it, then reload it when the AJAX call is made to execute the original shortcode. Since Dynamic Shortcode is a black box proxy, it <b>does not</b> alter the original request in any way. While make sure that no PHP remote code execution occur while the parameters are sent through the Dynamic Shortcode proxy, it is <b>your responsibility</b> to validate, escape and sanitize any parameter sent to your shortcode. It is also your <b>your responsibility</b> to make sure that the web request is legitimate.


== Screenshots ==

1. Dynamic Shortcode - Shortcodes
2. Dynamic Shortcode - Placeholders
3. Dynamic Shortcode - Global Settings
4. Dynamic Shortcode - Keep It Alive
5. Dynamic Shortcode - Notice
6. Dynamic Shortcode - Help

== Changelog ==


== Upgrade Notice ==


[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9PG7333GR22ZA)

# DynamicShortcode
Make any Wordpress shortcode asynchronous - Eliminate render-blocking shortcodes, improve user experience and Google PageSpeed Insights.

# Description
Dynamic Shortcode is a 100% **FREE** plugin that allows users to make any shortcode asynchronous in one simple step, meaning it won’t block the WordPress page render at all. Dynamic Shortcode has a simple and user friendly interface, making it extremely easy to use.

Dynamic Shortcode can either replace every occurence of a given shortcode without your intervention (currently in beta, the feature is called Dynamic Replace), or you can change every shortcode yourself in one simple step:
* Find an occurence of your shortcode, and replace your shortcode’s name with wp_dynamic and add a parameter named shortcode with your shortcode’s name as a value. For example:

  **[myShortcode]**

  becomes

  **[wp_dynamic shortcode=”myShortcode”]**
  
  
But wait! That’s not all: You can even add custom placeholders for each individual shortcode. Placeholders are displayed when Dynamic Shortcode is loading a shortcode asynchronously.

Dynamic Shortcode is packed with tons of other useful features.

For each individual shortcode on your site, here’s everything Dynamic Shortcode can do for you:

* Make any already existing shortcode asynchronous automatically or in a single step
* Add custom placeholders for when your Dynamic Shortcode is loading your shortcode
* Run a validation function before executing your shortcode when making the asynchronous call
* Choose which GET parameters to ignore when doing the asynchronous call
* Choose which POST parameters to ignore when doing the asynchronous call
* Choose which shortcode attribute to ignore when doing the asynchronous call

# Screenshots
Shortcodes Tab:
![Shortcodes Tab](https://ps.w.org/dynamic-shortcode-ajax/assets/screenshot-1.png?rev=2452258 "Shortcodes Tab")

Placeholders Tab:
![Placeholders Tab](https://ps.w.org/dynamic-shortcode-ajax/assets/screenshot-2.png?rev=2452258 "Placeholders Tab")

Global Settings Tab:
![Global Settings Tab](https://ps.w.org/dynamic-shortcode-ajax/assets/screenshot-3.png?rev=2452258 "Global Settings Tab")

Keep It Alive Tab:
![Keep It Alive Tab](https://ps.w.org/dynamic-shortcode-ajax/assets/screenshot-4.png?rev=2452258 "Keep It Alive Tab")

Notice Tab:
![Notice Tab](https://ps.w.org/dynamic-shortcode-ajax/assets/screenshot-5.png?rev=2452258 "Notice Tab")

Help Tab:
![Help Tab](https://ps.w.org/dynamic-shortcode-ajax/assets/screenshot-6.png?rev=2452258 "Help Tab")


# FAQ
### 1. Where is the menu located? ###
Go in your wp-admin page. Look at the admin menu on your left, you should see a tab “Shortcodes” with the Dynamic Shortcode logo.

### 2. If I use the Dynamic Replace setting, will it break my site? ###
Dynamic Replace will not permanently change your html pages: it works by replacing the shortcodes dynamically before each page load. Thus, it will not overwrite your html pages in any way.

### 3. Does Dynamic Shortcode sanitize, validate and/or escape my data? ###
Dynamic Shortcode act as a black box proxy: It takes the very state of the request while it tries to call the original shortcode, saves it, then reload it when the AJAX call is made to execute the original shortcode. Since Dynamic Shortcode is a black box proxy, it does not alter the original request in any way. While make sure that no PHP remote code execution occur while the parameters are sent through the Dynamic Shortcode proxy, it is your responsibility to validate, escape and sanitize any parameter sent to your shortcode. It is also your your responsibility to make sure that the web request is legitimate.



### In order to keep Dynamic Shortcode free and actively developed, we rely on your goodwill. Any amount is greatly appreciated and help us to improve Dynamic Shortcode by adding new features and keeping it up-to-date! ###
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9PG7333GR22ZA)

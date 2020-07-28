=== Plugin Name ===
Contributors: Thomas Hoadley
Tags: posts, featured
Requires at least: 5.4.2
Tested up to: 5.4.2
Requires PHP: 7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to feature posts as breaking news on your website.

== Installation ==

1. Upload `th-breaking-news` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You can then visit the settings page Under Settings > Breaking News to adjust the plugin defaults.
4. You can set posts as Breaking News under the posts admin page.

== Frequently Asked Questions ==

= How do I use this plugin? =

You can set posts as breaking news under their admin page in the back end. If there are no posts set as Breaking News or it has expired, nothing will display on the front-end.

= How do I change the plugin defaults? = 

This includes the background and font colour, the banner title, positioning of the bar and also adding a blinking animation. You can do this by visiting Settings > Breaking News. There are some defaults in place so please leave blank if you want to use these. 

= How do I move the Breaking News bar to a place of my choice? =

Please add the CSS selector which you would like the bar added after, in the plugin settings page. Generally, after the site header is a good place. By default, the bar displays at the very top of the website. It must be a valid selector, otherwise it will display at the top of the website. You may need a developer to help you find the correct CSS selector. 

= What happens to old Breaking News posts =

When you set a new post as Breaking News, it unsets the previous Breaking News post. Alternatively, you can set an expiry date for the breaking News. You can do this by ticking 'Set an expiry date for this post' and then adding your desired date and time.

= How do I set the time to my timezone? =

Please set your timezone in Settings > General > Timezone. e.g. the UK BST time is UTC + 1.

= How do I uninstall this plugin? =

To uninstall this plugin go to the admin panel > Plugins then click uninstall on the plugin. Please note this will remove all plugin data.
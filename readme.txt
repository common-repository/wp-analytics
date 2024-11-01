=== Wordpress Google Analytics Reports ===
Tags: Analytics, Google, Reports, Administration, SEO, Dashboard
Contributors: imthiaz
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5331475
Requires at least: 2.7.1
Tested up to: 3.9
Stable tag: 1.0.7

== Description ==
This plugin helps the users brings Google Analytics reports overview using Google Analytics Data API to your blog dashboard. Currently this plugin supports timeline and map overlay charts. The destination of the plugin is to help blogger to get insight of their blog traffic without logging into Analytics. 

Things to do
1. To support caching since there is a limit on API access per day.
2. To do a simple dashboard and theme widget
3. Add more Charts

== Installation ==
1. Copy files to wordpress wp-content/plugins folder.
2. Goto to Plugins page in wordpress and activate the Wordpress Google Analytics Reports plugin.
3. Goto 'Analytics Configuration' page and provide your google analytics email and password. Once you save it you will be able to select the profile. Once profile is selected you can view the report in 'Analytics Report' page under Dashboard.

== Frequently Asked Questions ==

= Why the plugin is not working for me ? =
Please check if you can select analytics profile in the option page. If you cannot then your email and password may be wrong or you don't have analytics profile attached to that email.

= Why my reports page is really slow ? =
This plugin queries Google Analytics to get the visitors information. This might take sometime. Working on a better way to cache the result for short duration.

= Should I modify any code in wordpress? =
Not needed. You have to just upload the files to your plugin directory.

= If I de-activate this plugin will it affect my blog? =
No. This plugin uses some of function of wordpress. Wordpress has nothing to with plugin. So de-activating this plugin is 100% safe.

== Changelog ==

= Version: 1.0.7 Dated: 2014-04-25 =
* Tested and released for wordpress 3.9

= Version: 1.0.6 Dated: 23-July-2009 =
* Updated version and checked compatibility with wordpress 2.8.2

= Version: 1.0.5 Dated: 20-May-2009 =
* Added js_escape to all strings printed in the javascript.

= Version: 1.0.4 Dated: 16-May-2009 =
* Removed CURLOPT_FOLLOWLOCATION due to limitation on safe mode
 
= Version: 1.0.3 Dated: 16-May-2009 =
* Added keywords, source, browsers table list
* Added requirement check if xml and curl enabled on the server
* Updated donation link
* Added changelog section in readme.txt

= Version: 1.0.2 Dated: 09-May-2009 =
* Updated Readme.txt with screenshots and donation links

= Version: 1.0.1 Dated: 09-May-2009 =
* Styled reports page
* Added report summary
* Added Visitors, New vists to annoted timeline chart
* Aded Map overlay visits chart
* Parser update and bug fixes

= Version: 1.0 Dated: 05-May-2009 =
* Visit / Pageviews Chart
* Google Data Feed fetcher
* Data parser 

== Screenshots ==

1. Screenshot of the configuration panel for this plugin.
2. Screenshot of reports page
=== Plugin Name ===
Contributors: Marijn Tijhuis, Fat Pixel
Donate link: http://www.fatpixel.nl/labs/lazyload-video
Tags: youtube, video, plugin, jquery, mobile, lazyload
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: master
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Lazyload video replaces the standard youtube embed with a clickable poster image. Youtube and it's scripts will be loaded after the user clicks.

== Description ==

This plugin hooks into the standard Youtube embed of wordpress, where it displays a placeholder with poster image instead of the actual embed code.

The Youtube iframe will get loaded once the user clicks the poster image. This can speed up your site immensely, especially when you have lots of video's.

Inspired by the great work on Lazy Load for Video's by KevinW.de

This plugin is still work in progress! Use at your own risk.

== Installation ==

Easily upload Lazyload Video into you plugin directory (/wp-content/plugins/) and activate the plugin through the 'Plugins' menu in WordPress.

You may have to clean the website's and browser's cache.

If you don't see a preview image instead of the Youtube video, open the article editor and update/save the article again. This should help.


== Frequently Asked Questions ==

This plugin might support other video formats in the future (work in progress).

If the plugin doesn't load properly after activiating, you might want to update the posts/pages where the video's are embedded.

== Changelog ==

= 0.2 =
* Some documentation, naming and commenting.
* Decided what to refactor

= 0.1 =
* First working implementation of the plugin


== Screenshots ==

1. Preview image and play button are displayed.

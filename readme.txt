=== Seriously Simple Speakers ===
Contributors: hlashbrooke, kbjohnson90
Tags: seriously simple podcasting, sponsors, podcast, podcasting, ssp, free, add-ons, extensions, addons
Requires at least: 4.4
Tested up to: 4.5.3
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add sponsors to your Seriously Simple Podcasting episodes.

== Description ==

> This plugin is an add-on for [Seriously Simple Podcasting](https://www.seriouslysimplepodcasting.com/) and requires at least **v1.14** of Seriously Simple Podcasting in order to work.

Does your podcast have a number of different sponsors? Or maybe a different sponsor each week? Perhaps you have unique sponsor for each episode? If any of those options describe your podcast then this is the add-on for you!

Seriously Simple Sponsors allows you to add one or more sponsors to each of your episodes - the sponsors are setup as a new taxonomy, making them easily searchable as well as giving them their own archive pages out of the box.

**Primary Features**

- Allows you to add any number of sponsors to your podcast episodes
- Adds a new `sponsor` taxonomy to all podcast post types
- Displays sponsors in the episode details with links to sponsors archives

**How to contribute**

If you want to contribute to Seriously Simple Sponsors, you can [fork the GitHub repository](#) - all pull requests will be reviewed and merged if they fit into the goals for the plugin.

== Installation ==

Installing "Seriously Simple Sponsors" can be done either by searching for "Seriously Simple Sponsors" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
1. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. The Sponsors taxonomy page in the admin menu
2. Sponsors are managed just like any other taxonomy
3. Sponsors are added to episodes in the same way as any other taxonomy
4. Sponsors are displayed in the episode details area by default (see the FAQs for how to disable this display)

== Frequently Asked Questions ==

= What version of Seriously Simple Podcasting does this plugin require? =

In order to use this plugin you need to have at least v1.14 of [Seriously Simple Podcasting](https://www.seriouslysimplepodcasting.com/). If you do not have Seriously Simple Podcasting active or you are using a version older than v1.14 then this plugin will do nothing.

= How can I retrieve a list of all the sponsors for an episode? =

If you want to get a list of sponsors for an episode to use in your templates then this function will return an array of the episode sponsors, along with their ID, display name and archive URL: `SSP_Sponsors()->get_sponsors( $episode_id );`. If you do not specify the `$episode_id` then the ID of the current post will be used.

= How do I hide the sponsors list from the episode details? =

If you would like to add sponsors to your episodes, but not have them displayed in the standard episode details location then simply add this code to your theme's functions.php file (or a functionality plugin): `add_filter( 'ssp_sponsors_display', '__return_false' );`

== Changelog ==

= 1.0 =
* 2016-00-00
* Initial release

== Upgrade Notice ==

= 1.0 =
* 2016-00-00
* Initial release

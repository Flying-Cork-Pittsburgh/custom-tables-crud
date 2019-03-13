=== WordPress Base Plugin ===
Contributors: hendridm
Tags: wordpress,base,plugin,boilerplate,composer,carbonfields
Donate link: https://paypal.me/danielhendricks
Requires at least: 4.6
Requires PHP: 5.6
Tested up to: 5.0
Stable tag: 0.4.0
License: GPL-2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is intended to be used as a boilerplate for creating WordPress plugins.

== Description ==
This is a boilerplate WordPress plugin featuring namespace autoloading and integration with [Carbon Fields](https://github.com/htmlburger/carbon-fields).

It is intended to be used as a starting point for creating WordPress plugins.

= Requirements =

* WordPress 4.6 or higher
* PHP 5.6 or higher
* [Carbon Fields](https://github.com/htmlburger/carbon-fields) 2.0 or higher (see the wiki section on [Carbon Fields](https://github.com/dmhendricks/wordpress-base-plugin/wiki#carbon-fields) for more info)

== Installation ==
If you need tips on installing Node.js, Composer, Gulp & Bower, see [Installing Dependencies](https://github.com/dmhendricks/wordpress-base-plugin/wiki/Installing-Dependencies).

= Clone Repository =

1. At command prompt, change to your `wp-content/plugins` directory.
2. Clone the repository: `git clone https://github.com/dmhendricks/wordpress-base-plugin.git`
3. Renamed the newly created `wordpress-base-plugin` directory to your own plugin slug.

= Next Steps =

See the [Getting Started](https://github.com/dmhendricks/wordpress-base-plugin/wiki#getting-started) documentation for further steps.

== Frequently Asked Questions ==
= Q. Why do I get the error "Warning: require( ... /autoload.php): failed to open stream: No such file or directory" when I try to activate it?
A. You need to use the command prompt and [run Composer](https://github.com/dmhendricks/wordpress-base-plugin#composer) before this plugin will work.

= Q. What is Composer? =
A. Composer is an application-level package manager for the PHP programming language that provides a standard format for managing dependencies of PHP software and required libraries.

== Screenshots ==
1. Settings Page






1. Now, open package.json and modify the following variables to values that are relevantely named for your plugin.

name / The slug for your plugin. I usually set this to match the base directory name.
config /
	username / While not imparative, this will change the GitHub username paths. If you don't have a GitHub account, you can change it to whatever you like (such as your WordPress.com username).
	php_namespace / A unique PHP namespace for your plugin. This value will replace the default VendorName\PluginName namespace used in the examples.
	WPBP_NS / To avoid conflicts, the JavaScript examples use a unique object that contains function logic. Rename this to something that makes sense for your plugin.

2. To install Node & Gulp dependencies:
$ npm install


3. To rename the strings that you set in the previous step, run (This will replace the namespaces, slugs and filenames to your new strings):
$ gulp rename


4. Finally, run the following comppand to install PHP dependencies used by the plugin into the vendor directory:
$ composer install


5. The main Gulp task is used to process, minify and combine SASS (CSS) and JavaScript files. In order to try this example plugin, you will want to run it at least once:
$ gulp




Files & Paths

app / The plugin-specific PHP files and classes are located here.
src / This folder holds SASS and JavaScript files that will eventually be process by Gulp and copied to the assets folder.
assets /
languages / If you create a Translation file for your plugin, the resulting .pot will be created here.



Other Files

.gitignore / If you are publishing your plugin to GitHub and want it to be usable without special commands (or updatable via GitHub Updater), you'll probably want to remove/comment out the "Development" section at the bottom. You may also wish to add .gitignore to the list.

plugin.json / Contains several global settings for the plugin. At minimum, you'll want to change the prefix value to something unique to your project (this is used to prefix all options added to the wp_options table). See Configuration & Constants for more information.

wordpress-base-plugin.php / This is the main plugin loader file. You will want to change any settings in the header meta relavent to your plugin. If you're not interested in supporting automatic updates via GitHub Updater, you'll want to remove the "GitHub Plugin URI" line.

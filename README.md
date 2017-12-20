## WordPress Admin Forms Demo
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Description
A Basic WordPress Plugin to explore form submission in WordPress for an accompanying article.

## Installation Manually
1. Download the latest archive and extract to a folder
2. Upload the plugin to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

## i18n Tools

The Plugin uses a variable to store the text domain used when internationalizing strings throughout the code. To take advantage of this method, there are tools that are recommended for providing correct, translatable files:

* [Poedit](http://www.poedit.net/)
* [makepot](http://i18n.svn.wordpress.org/tools/trunk/)
* [i18n](https://github.com/grappler/i18n)

Any of the above tools should provide you with the proper tooling to internationalize the plugin.

However, if you still face problems translating the strings with an automated tool/process, replace `$this->plugin_text_domain` with the literal string of your plugin's text domain.

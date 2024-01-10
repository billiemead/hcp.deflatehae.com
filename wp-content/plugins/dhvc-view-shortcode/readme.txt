Installation
============
Upload the plugin zip file in the `plugin` folder to your WordPress site and activate it.

Usage
=====
After you installed and activate the plugin, a new control button will add for each shortcode element in visual composer.
if WP Widget Text not working with shortcode. please go to file "{yourtheme}/functions.php" add add filter:

//allow shortcodes in text widget
add_filter('widget_text', 'do_shortcode');

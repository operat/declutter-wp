<?php
/*
Plugin Name: Declutter WP
Plugin URI: https://github.com/operat/declutter-wp
GitHub Plugin URI: https://github.com/operat/declutter-wp
Description: Remove unnecessary clutter from WordPress to improve security and performance.
Version: 1.0
Author: Operat
Author URI: https://www.operat.de
License: MIT
License URI: https://opensource.org/licenses/MIT
*/

// Make sure plugin can't be accessed directly
if (!defined('WPINC')) { die; }

define('DECLUTTER_WP_NAME', 'Declutter WP');
define('DECLUTTER_WP_DESCRIPTION', 'Remove unnecessary clutter from WordPress to improve security and performance.');
define('DECLUTTER_WP_URL', 'https://github.com/operat/declutter-wp');

require_once 'DeclutterWP.Declutter.php';
require_once 'DeclutterWP.PluginManager.php';

add_action('init', array('DeclutterWP_PluginManager', 'init'));
register_activation_hook(__FILE__, array('DeclutterWP_PluginManager', 'setDefaultOptions'));

if (is_admin()) {
   require_once 'DeclutterWP.SettingsPage.php';
   $settingsPage = new DeclutterWP_SettingsPage();
}



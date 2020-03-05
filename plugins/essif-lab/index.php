<?php
/**
 * Plugin Name:       Fix Content Links
 * Plugin URI:        http://wordpress.org/plugins/fix-content-links/
 * Description:       Replaces incorrect links to sources located in the uploads, plugins, themes or wp-content folder.
 * Version:           1.0.0
 * Author:            LSVH
 * Author URI:        https://lsvh.org/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fix-content-links
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) or die();

// Load all classes of this program automatically
require plugin_dir_path( __FILE__ ) . '../../vendor/autoload.php';

// Import the class to initiate the plugin
use LSVH\WordPress\FixContentLinks\Setup;

// Make sure the WP Plugin API is loaded
if (!function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

// Initiate the plugin by creating an instance
new Setup(array_merge(
    get_plugin_data(__FILE__, false, false),
    [
        'PluginPath' => plugin_dir_path(__FILE__),
        'PluginParentPage' => 'settings',
    ]
));
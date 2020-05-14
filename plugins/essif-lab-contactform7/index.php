<?php
/**
 * Plugin Name: eSSIF-Lab-ContactForm7
 * Plugin URI: https://github.com/LSVH/hbo-ict-inno-s2-tno
 * Description: Subplugin to support ContactForm7 in the eSSIF-Lab plugin.
 * Version: 1.0
 * Author: Duur Klop, Luuk van Houdt, Ruben Sikkens, Ufuk Altinçöp en Weis Mateen
 */

defined( 'ABSPATH' ) or die();

// Make sure the WP Plugin API is loaded
if (!function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

// Initialize requirements
require_once('Controllers/CF7Button.php');
require_once('Controllers/Script.php');
require_once('Controllers/Hooks.php');
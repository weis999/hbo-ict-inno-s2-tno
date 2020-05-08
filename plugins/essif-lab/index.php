<?php
/**
 * Plugin Name: eSSIF-Lab
 * Plugin URI: https://github.com/LSVH/hbo-ict-inno-s2-tno
 * Description: The purpose of the eSSIF-Lab is to specify, develop and validate technological and non-technological means that support people, businesses and governments to think about, design and operate their (information) processes and (electronically) conduct business transactions with one another.
 * Author: Duur Klop, Luuk van Houdt, Ruben Sikkens, Ufuk Altinçöp en Weis Mateen
 * TextDomain: tno-essif-lab
 * Version: 1.0
 */

defined( 'ABSPATH' ) or die();

// Load all classes of this program automatically
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

// Import the class to initiate the plugin
use TNO\EssifLab\Application\Main;

// Make sure the WP Plugin API is loaded
if (!function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

// Initiate the plugin by creating an instance
new Main(array_merge(
    get_plugin_data(__FILE__, false, false),
    [
        'PluginPath' => plugin_dir_path(__FILE__),
    ]
));
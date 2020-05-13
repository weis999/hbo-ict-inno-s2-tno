<?php
/**
 * Plugin Name: eSSIF-Lab
 * Plugin URI: https://github.com/LSVH/hbo-ict-inno-s2-tno
 * Description: The purpose of the eSSIF-Lab is to specify, develop and validate technological and non-technological means that support people, businesses and governments to think about, design and operate their (information) processes and (electronically) conduct business transactions with one another.
 * Author: Duur Klop, Luuk van Houdt, Ruben Sikkens, Ufuk Altinçöp en Weis Mateen
 * TextDomain: tno-essif-lab
 * Version: 1.0
 */

defined('ABSPATH') or die();

// Load all classes of this program automatically
require plugin_dir_path(__FILE__).'vendor/autoload.php';

use TNO\EssifLab\Applications\Contracts\Application;
use TNO\EssifLab\Applications\Plugin;
use TNO\EssifLab\ModelManagers\Contracts\ModelManager;
use TNO\EssifLab\ModelManagers\WordPressPostTypes;
use TNO\EssifLab\Integrations\Contracts\Integration;
use TNO\EssifLab\Integrations\WordPress;

// Make sure the WP Plugin API is loaded
if (! function_exists('get_plugin_data')) {
	require_once(ABSPATH.'wp-admin/includes/plugin.php');
}

$getApplication = function (): Application {
	$name = function (): string {
		$pluginData = get_plugin_data(__FILE__, false, false);

		return array_key_exists('Name', $pluginData) ? $pluginData['Name'] : 'App';
	};

	$namespace = function (): string {
		$pluginData = get_plugin_data(__FILE__, false, false);

		return array_key_exists('TextDomain', $pluginData) ? $pluginData['TextDomain'] : 'MyApp';
	};

	$appDir = function (): string {
		return plugin_dir_path(__FILE__);
	};

	return new Plugin($name(), $namespace(), $appDir());
};

$getModelManager = function (Application $application): ModelManager {
	return new WordPressPostTypes($application);
};

$getIntegration = function (Application $application) use ($getModelManager): Integration {
	return new WordPress($application, $getModelManager($application));
};

// Install the integration
$getIntegration($getApplication())->install();
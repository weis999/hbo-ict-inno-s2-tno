<?php

namespace TNO\EssifLab\Abstracts;

defined('ABSPATH') or die();

use TNO\EssifLab\Interfaces\Core as ICore;

abstract class Core implements ICore {
	/**
	 * The full name of the plugin as shown in the plugins list.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The version number of this plugin.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * The text domain mainly used to identify translatable strings.
	 *
	 * @var string
	 */
	private $domain;

	/**
	 * The absolute path to the plugin directory.
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Custom options set by the administrator of the plugin.
	 *
	 * @var array
	 */
	private $options = [];

	/**
	 * All plugin data that initiated the plugin.
	 *
	 * @var array
	 */
	private $pluginData = [];

	/**
	 * CoreAbstract constructor.
	 *
	 * @param array $pluginData
	 */
	public function __construct($pluginData = []) {
		$this->pluginData = $pluginData;
		$this->name = $this->getPluginDataValue('Name');
		$this->version = $this->getPluginDataValue('Version');
		$this->domain = $this->getPluginDataValue('TextDomain');
		$this->path = $this->getPluginDataValue('PluginPath');
		$this->options = function_exists('get_option') ? get_option($this->getDomain()) : [];
	}

	/**
	 * The full name of the plugin as shown in the plugins list.
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * The version number of this plugin.
	 *
	 * @return string
	 */
	public function getVersion(): string {
		return $this->version;
	}

	/**
	 * The text domain mainly used to identify translatable strings.
	 *
	 * @return string
	 */
	public function getDomain(): string {
		return $this->domain;
	}

	/**
	 * The absolute path to the plugin directory.
	 *
	 * @return string
	 */
	public function getPath(): string {
		return $this->path;
	}

	/**
	 * Get all the options
	 *
	 * @return array
	 */
	public function getOptions(): array {
		return $this->options;
	}

	/**
	 * Retrieve an option from the `$options` array.
	 *
	 * @param string $key
	 * @return mixed|null
	 */
	public function getOption($key) {
		return is_array($this->options) && array_key_exists($key, $this->options) ? $this->options[$key] : '';
	}

	/**
	 * Check if an option exists
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function hasOption($key) {
		return is_array($this->options) && array_key_exists($key, $this->options);
	}

	/**
	 * Add or update an option
	 *
	 * @param string $key
	 * @param null|mixed $value
	 * @return void
	 */
	public function updateOption($key, $value = null) {
		$newOptions = $this->getOptions();
		$newOptions[$key] = $value;
		update_option($this->getDomain(), $newOptions);
		$this->options = $newOptions;
	}

	/**
	 * Batch add or update options
	 *
	 * @param $options
	 * @return void
	 */
	public function updateOptions($options) {
		$updatedOptions = array_merge($this->getOptions(), $options);
		update_option($this->getDomain(), $updatedOptions);
		$this->options = $updatedOptions;
	}

	/**
	 * Get the plugin data
	 *
	 * @return array
	 */
	public function getPluginData(): array {
		return $this->pluginData;
	}

	/**
	 * Retrieve a value of specific piece of `$plugin_data`.
	 *
	 * @param string $key
	 * @return mixed|null
	 */
	private function getPluginDataValue($key) {
		return is_array($this->pluginData) && array_key_exists($key, $this->pluginData) ? $this->pluginData[$key] : null;
	}
}
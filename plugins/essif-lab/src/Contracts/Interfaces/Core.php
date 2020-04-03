<?php

namespace TNO\EssifLab\Contracts\Interfaces;

defined('ABSPATH') or die();

interface Core {
	/**
	 * Get all the stored options.
	 *
	 * @return array
	 */
	function getOptions(): array;

	/**
	 * Get a specific stored option.
	 *
	 * @param $key
	 * @return mixed
	 */
	function getOption($key);

	/**
	 * Get all information about this plugin.
	 *
	 * @return array
	 */
	function getPluginData(): array;

	/**
	 * Get the full name.
	 *
	 * @return string
	 */
	function getName(): string;

	/**
	 * Get the version.
	 *
	 * @return string
	 */
	function getVersion(): string;

	/**
	 * Get the text domain used by this plugin.
	 *
	 * @return string
	 */
	function getDomain(): string;

	/**
	 * Get the absolute path to this plugin.
	 *
	 * @return string
	 */
	function getPath(): string;
}
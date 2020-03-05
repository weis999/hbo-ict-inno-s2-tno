<?php

namespace LSVH\WordPress\FixContentLinks\Extendables;

defined('ABSPATH') or die();

use LSVH\WordPress\FixContentLinks\Contracts\CoreInterface;
use LSVH\WordPress\FixContentLinks\Contracts\CoreSettingsInterface;
use LSVH\WordPress\DynamicConfig\Init as DynamicConfig;

abstract class CoreAbstract implements CoreInterface, CoreSettingsInterface
{
    /**
     * The full name of the plugin as shown in the plugins list.
     *
     * @var string
     */
    private $name;

    /**
     * The parent page of the plugin's option page.
     *
     * @var string
     */
    private $plugin_parent_page;

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
     * All plugin data what initiated the plugin.
     *
     * @var array
     */
    private $plugin_data = [];

    /**
     * CoreAbstract constructor.
     *
     * @param array $plugin_data
     */
    public function __construct($plugin_data = [])
    {
        $this->plugin_data = $plugin_data;
        $this->name = $this->get_plugin_data_value('Name');
        $this->version = $this->get_plugin_data_value('Version');
        $this->domain = $this->get_plugin_data_value('TextDomain');
        $this->path = $this->get_plugin_data_value('PluginPath');
        $this->plugin_parent_page = $this->get_plugin_data_value('PluginParentPage');
        $this->options = function_exists('get_option') ? get_option($this->get_domain()) : [];
    }

    /**
     * The full name of the plugin as shown in the plugins list.
     *
     * @return string
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * The version number of this plugin.
     *
     * @return string
     */
    public function get_version(): string
    {
        return $this->version;
    }

    /**
     * The text domain mainly used to identify translatable strings.
     *
     * @return string
     */
    public function get_domain(): string
    {
        return $this->domain;
    }

    /**
     * The absolute path to the plugin directory.
     *
     * @return string
     */
    public function get_path(): string
    {
        return $this->path;
    }

    /**
     * The parent page of the plugin's option page.
     *
     * @return string
     */
    public function get_plugin_parent_page(): string
    {
        return $this->plugin_parent_page;
    }

    /**
     * Get all the options
     *
     * @return array
     */
    public function get_options(): array
    {
        $options = empty($this->options) ? [] : $this->options;
        return array_merge([
            self::FIELD_TYPE => $this->get_option_default(self::FIELD_TYPE),
            self::FIELD_PATH => $this->get_option_default(self::FIELD_PATH),
            self::FIELD_EXCLUDE => $this->get_option_default(self::FIELD_EXCLUDE),
        ], $options);
    }

    /**
     * Retrieve an option from the `$options` array.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get_option($key)
    {
        $options = $this->get_options();
        return is_array($options) && array_key_exists($key, $options) ?
            $options[$key] : $this->get_option_default($key);
    }

    /**
     * Check if an option exists
     *
     * @param string $key
     * @return mixed
     */
    public function has_option($key) {
        return is_array($this->options) && array_key_exists($key, $this->options);
    }

    /**
     * Add or update an option
     *
     * @param string $key
     * @param null|mixed $value
     * @return void
     */
    public function update_option($key, $value = null) {
        $options = $this->get_options();
        $options[$key] = $value;
        update_option($this->get_domain(), $options);
        $this->options = $options;
    }

    /**
     * Batch add or update options
     *
     * @param string $key
     * @param null|mixed $value
     * @return void
     */
    public function update_options($options) {
        $options = array_merge($this->get_options(), $options);
        update_option($this->get_domain(), $options);
        $this->options = $options;
    }

    /**
     * Get the default value of an option.
     *
     * @param $key
     * @return mixed
     */
    public function get_option_default($key)
    {
        switch ($key) {
            case self::FIELD_TYPE:
                return 'temporary';

            case self::FIELD_PATH:
                $without = DynamicConfig::url_without_path();
                $with = DynamicConfig::url_with_path();
                return substr($with, strlen($without));

            default:
                return '';
        }
    }

    /**
     * Get the plugin data
     *
     * @return array
     */
    public function get_plugin_data(): array
    {
        return $this->plugin_data;
    }

    /**
     * Retrieve a value of specific piece of `$plugin_data`.
     *
     * @param string $key
     * @return mixed|null
     */
    private function get_plugin_data_value($key)
    {
        return is_array($this->plugin_data) && array_key_exists($key, $this->plugin_data) ? $this->plugin_data[$key] : null;
    }
}
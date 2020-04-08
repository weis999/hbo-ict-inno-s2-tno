<?php

namespace TNO\EssifLab\Application\Workflows\Constructors;

defined('ABSPATH') or die();

interface CoreInterface
{
    /**
     * Get all the stored options.
     *
     * @return array
     */
    function get_options(): array;

    /**
     * Get a specific stored option.
     *
     * @param $key
     * @return mixed
     */
    function get_option($key);

    /**
     * Get the default value of an option.
     *
     * @param $key
     * @return mixed
     */
    function get_option_default($key);

    /**
     * Get all information about this plugin.
     *
     * @return array
     */
    function get_plugin_data(): array;

    /**
     * Get the full name.
     *
     * @return string
     */
    function get_name(): string;

    /**
     * Get the version.
     *
     * @return string
     */
    function get_version(): string;

    /**
     * Get the text domain used by this plugin.
     *
     * @return string
     */
    function get_domain(): string;

    /**
     * Get the absolute path to this plugin.
     *
     * @return string
     */
    function get_path(): string;
}
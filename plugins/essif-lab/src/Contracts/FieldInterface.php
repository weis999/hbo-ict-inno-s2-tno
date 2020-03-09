<?php

namespace TNO\EssifLab\Contracts;

defined('ABSPATH') or die();

interface FieldInterface
{
    /**
     * Render and print the HTML to display the field.
     *
     * @return void
     */
    function render(): void;

    /**
     * Sanitize the value to minimize system failures.
     *
     * @param mixed $value
     * @return mixed
     */
    function sanitize($value);

    /**
     * Check if the submitted value is valid.
     *
     * @param string $value What to check.
     * @return bool
     */
    function validate($value): bool;

    /**
     * Get the order of the field.
     *
     * @return int
     */
    function get_order(): int;

    /**
     * Get the ID of the field.
     *
     * @return string
     */
    function get_id(): string;

    /**
     * Get the name of the field.
     *
     * @return string
     */
    function get_name(): string;

    /**
     * Get the type of the field.
     *
     * @return string
     */
    function get_type(): string;

    /**
     * Get the label of the field.
     *
     * @return string
     */
    function get_label(): string;

    /**
     * Get the value of the field.
     *
     * @return string
     */
    function get_value(): string;
}
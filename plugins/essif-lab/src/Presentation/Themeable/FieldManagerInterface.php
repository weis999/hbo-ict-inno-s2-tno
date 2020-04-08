<?php

namespace TNO\EssifLab\Presentation\Themeable;

defined('ABSPATH') or die();

interface FieldManagerInterface
{
    /**
     * Retrieve the one and single instance.
     *
     * @return FieldManagerInterface
     */
    static function getInstance(): FieldManagerInterface;

    /**
     * Retrieve the whole list of fields.
     *
     * @return FieldInterface[]
     */
    function all(): array;

    /**
     * Retrieve a field from the list of fields.
     *
     * @param string $id
     * @return FieldInterface
     */
    function get(string $id): FieldInterface;

    /**
     * Check the existence of a specific field.
     *
     * @param string $id
     * @return bool
     */
    function has(string $id): bool;

    /**
     * Add a new field to the list of fields.
     *
     * @param array $args {
     *      Data used to create the field from.
     *
     * @type int $order The position of the field in the form.
     * @type string $id The key used to store the value of the field.
     * @type string $name The key used to identify the submitted field.
     * @type string $type The type of field to display.
     * @type string $label The label of the field.
     * @type string $value The value of the field.
     * @type array $options The list of options for the field.
     * }
     */
    function add(array $args): void;

    /**
     * Update a specific field in the list of fields.
     *
     * @param array $args {
     *      Data used to update the field from.
     *
     * @type int $order The position of the field in the form.
     * @type string $id The key used to store the value of the field.
     * @type string $name The key used to identify the submitted field.
     * @type string $type The type of field to display.
     * @type string $label The label of the field.
     * @type string $value The value of the field.
     * @type array $options The list of options for the field.
     * }
     */
    function update(array $args): void;
}
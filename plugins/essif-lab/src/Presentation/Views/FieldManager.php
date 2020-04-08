<?php

namespace TNO\EssifLab\Presentation\Views;

defined('ABSPATH') or die();

use TNO\EssifLab\Presentation\Themeable\FieldInterface;
use TNO\EssifLab\Presentation\Themeable\FieldManagerAbstract;
use TNO\EssifLab\Presentation\Views\FieldTypes\SelectBoxes;
use TNO\EssifLab\Presentation\Views\FieldTypes\TextArea;
use TNO\EssifLab\Presentation\Views\FieldTypes\TextField;

class FieldManager extends FieldManagerAbstract
{
    const OPTIONS = 'options';
    /**
     * @var FieldInterface[] $fields A list of fields.
     */
    protected $fields = [];

    /**
     * Retrieve the list of fields.
     *
     * @return FieldInterface[]
     */
    public function all(): array
    {
        return $this->fields;
    }

    /**
     * Check the existence of a field.
     *
     * @param string $id The field ID to search for.
     * @return bool
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->fields) && ! empty($this->fields[$id]);
    }

    /**
     * Retrieve a specific field from the list.
     *
     * @param string $id The field ID to retrieve.
     * @return FieldInterface|null
     */
    public function get(string $id): FieldInterface
    {
        $field = array_filter($this->fields, function (FieldInterface $item) use ($id) {
            return $item->get_id() === $id;
        });

        return ! empty($field) && is_array($field) && array_key_exists(array_keys($field)[0], $field) ? $field[array_keys($field)[0]] : null;
    }

    /**
     * Add a new field to the list.
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
    public function add(array $args): void
    {
        $id = self::get_arg('id', $args);
        if (! $this->has($id)) {
            $this->fields[$id] = $this->create_field($args);
        } else {
            $this->update($args);
        }
        $this->sort_fields();
    }

    /**
     * Update an existing field in the list.
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
    public function update(array $args): void
    {
        $id = self::get_arg('id', $args);
        if ($this->has($id)) {
            $this->fields[$id] = $this->create_field($args);
            $this->sort_fields();
        }
    }

    /**
     * Reorder the list of fields based on the order of the field.
     */
    private function sort_fields(): void
    {
        $this->fields = array_filter($this->fields);
        uasort($this->fields, function (FieldInterface $a, FieldInterface $b) {
            return $a->get_order() - $b->get_order();
        });
    }

    /**
     * Create a field object of the relevant type.
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
     * @return FieldInterface
     */
    private function create_field(array $args): FieldInterface
    {
        $args = $this->parse_args($args);
        $order = self::get_arg('order', $args);
        $id = self::get_arg('id', $args);
        $name = self::get_arg('name', $args);
        $label = self::get_arg('label', $args);
        $type = self::get_arg('type', $args);
        $value = self::get_arg('value', $args);
        $options = self::get_arg(self::OPTIONS, $args);
        if (in_array($type, SelectBoxes::$types)) {
            $field = new SelectBoxes($order, $id, $name, $type, $label, $value, $options);
        } else if (in_array($type, TextArea::$types)) {
            $field = new TextArea($order, $id, $name, $label, $value);
        } else {
            $field = new TextField($order, $id, $name, $type, $label, $value);
        }

        return $field;
    }

    /**
     * Ensure the all required arguments are set.
     *
     * @param array $args
     * @return array
     */
    private function parse_args(array $args): array
    {
        $defaults = [
            'order' => count($this->fields) + 1,
            'id' => spl_object_hash($this),
            'name' => sprintf('wordpress[%s]', spl_object_hash($this)),
            'type' => 'text',
            'label' => '',
            'value' => null,
            self::OPTIONS => [],
        ];

        // Remove empty values
        $args = array_filter(array_merge($defaults, $args));
        if (array_key_exists(self::OPTIONS, $args)) {
            $args[self::OPTIONS] = array_filter($args[self::OPTIONS]);
        }

        return $args;
    }

    /**
     * Get the value of a specific argument.
     *
     * @param string $key
     * @param array $args
     * @return mixed|null
     */
    private static function get_arg(string $key, array $args)
    {
        return is_array($args) && array_key_exists($key, $args) ? $args[$key] : null;
    }
}
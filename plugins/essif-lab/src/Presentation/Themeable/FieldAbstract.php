<?php

namespace TNO\EssifLab\Presentation\Themeable;

use TNO\EssifLab\Presentation\Themeable\FieldInterface;

defined('ABSPATH') or die();

abstract class FieldAbstract implements FieldInterface
{
    /**
     * @var int $order The position of the field in the form.
     */
    protected $order;

    /**
     * @var string $id The key used to store the value of the field.
     */
    protected $id;

    /**
     * @var string $name The key used to identify the submitted field.
     */
    protected $name;

    /**
     * @var string $type The type of field to display.
     */
    protected $type;

    /**
     * @var string $label The label of the field.
     */
    protected $label;

    /**
     * @var string $value The value of the field.
     */
    protected $value;

    /**
     * FieldAbstract constructor.
     *
     * @param $order
     * @param $id
     * @param $name
     * @param $type
     * @param $label
     * @param $value
     */
    public function __construct($order, $id, $name, $type, $label, $value)
    {
        $this->order = $order;
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->label = $label;
        $this->value = $value;
    }

    /**
     * The order of the field in the form.
     *
     * @return int
     */
    public function get_order(): int
    {
        return $this->order;
    }

    /**
     * The key used to store the value of the field.
     *
     * @return string
     */
    public function get_id(): string
    {
        return $this->id;
    }

    /**
     * The key used to identify the submitted field.
     *
     * @return string
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * The type of field to display.
     *
     * @return string
     */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
     * The label of the field.
     *
     * @return string
     */
    public function get_label(): string
    {
        return $this->label;
    }

    /**
     * The value of the field.
     *
     * @return string
     */
    public function get_value(): string
    {
        return $this->value;
    }

    /**
     * Check if the submitted value is valid.
     *
     * @param string $value What to check.
     * @return bool
     */
    public function validate($value): bool
    {
        return true;
    }
}
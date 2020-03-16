<?php

namespace TNO\EssifLab\Models\FieldTypes;

defined('ABSPATH') or die();

use TNO\EssifLab\Extendables\FieldAbstract;

class SelectBoxes extends FieldAbstract
{
    public static $types = ['checkbox', 'radio'];

    protected $options = [];

    public function __construct($order, $id, $name, $type, $label, $value, $options)
    {
        parent::__construct($order, $id, $name, $type, $label, $value);
        $this->options = $options;
    }

    public function render(): void
    {
        $options = $this->options;
        if (! empty($options) && is_array($options)) {
            $stored_value = self::select_box_option($this->value, $options);
            foreach ($options as $widget_value => $widget_label) {
                print '<p>'.self::widget($this->name, $this->type, $widget_label, $widget_value, $stored_value).'</p>';
            }
        }
    }

    private static function widget($name, $type, $label, $widget_value, $stored_value)
    {
        $type = empty($type) ? self::$types[0] : $type;
        $checked = $stored_value === $widget_value ? 'checked ' : '';

        return sprintf('<label><input type="%s" name="%s" value="%s" %s/> %s</label>', $type, $name, $widget_value, $checked, $label);
    }

    public function sanitize($value)
    {
        return self::select_box_option($value, $this->options);
    }

    private static function select_box_option($key, $options)
    {
        $options = is_array($options) ? array_keys($options) : [];

        $alternative = array_key_exists(0, $options) ? $options[0] : null;
        return in_array($key, $options) ? $key : $alternative;
    }
}
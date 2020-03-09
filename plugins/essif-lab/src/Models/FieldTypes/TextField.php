<?php

namespace TNO\EssifLab\Models\FieldTypes;

defined('ABSPATH') or die();

use TNO\EssifLab\Extendables\FieldAbstract;

class TextField extends FieldAbstract
{
    public static $types = ['text', 'number', 'email', 'telephone'];

    public function render(): void
    {
        print '<p>'.$this->widget($this->name, $this->type, $this->value).'</p>';
    }

    private static function widget($name, $type, $value)
    {
        $type = empty($type) ? self::$types[0] : $type;

        return sprintf('<input type="%s" name="%s" value="%s"/>', $type, $name, $value);
    }

    public function sanitize($value)
    {
        return sanitize_text_field($value);
    }
}
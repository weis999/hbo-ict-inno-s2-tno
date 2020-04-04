<?php

namespace TNO\EssifLab\Presentation\Components;

use TNO\EssifLab\Contracts\Abstracts\Component;
use TNO\EssifLab\Contracts\Interfaces\Core;

class FormControl extends Component {
	private $name = '';

	private $fields = [];

	private $typedTags = ['select', 'textarea', 'button'];

	public function __construct(Core $plugin, $args = []) {
		parent::__construct($plugin);

		foreach ($args as $key => $value) {
			if (property_exists($this, $key) && gettype($this->{$key}) === gettype($value)) {
				$this->{$key} = $value;
			}
		}
	}

	public function render(): string {
		return $this->renderFields();
	}

	public function getRenderedFields($separated = false): array {
		$output = [];
		if (is_array($this->fields) && count($this->fields)) {
			foreach ($this->fields as $field) {
				$output[] = $this->renderField($field, $separated);
			}
		}

		return $output;
	}

	protected function renderFields(): string {
		return join('', $this->getRenderedFields());
	}

	protected function getFieldAttributesArray($field, $keys) {
		$attrs = [];
		if (is_array($keys) && is_array($field)) {
			foreach ($keys as $key) {
				$attrs[] = $this->getFieldAttribute($field, $key);
			}
		}

		return $attrs;
	}

	protected function getFieldAttribute($field, $key) {
		return is_array($field) && array_key_exists($key, $field) ? $field[$key] : null;
	}

	protected function renderField($field, $separated = false) {
		[$inputName, $type, $label, $value, $children, $class] = $this->getFieldAttributesArray($field, [
			'name',
			'type',
			'label',
			'value',
			'children',
			'class',
		]);

		$isTypedTag = array_search($type, $this->typedTags) !== false;
		$inputName = $this->name."[$inputName]";
		$tag = $isTypedTag ? $type : 'input';
		$type = $isTypedTag ? null : $type;
		$attrs = self::generateElementAttrs([
			'name' => $inputName,
			'type' => $type,
			'class' => $class,
			'value' => $value,
		]);

		$label = $this->renderLabel($inputName, $label);
		$input = $isTypedTag ? $this->renderFieldWithChildren($tag, $attrs, $children) : "<$tag$attrs/>";

		return $separated ? [$label, $input] : $label.$input;
	}

	protected function renderLabel($for, $children) {
		$attrs = self::generateElementAttrs(['for' => $for]);

		return empty($children) ? '' : "<label$attrs>$children</label>";
	}

	protected function renderFieldWithChildren($tag, $attrs, $children) {
		if (is_array($children)) {
			$childrenArray = $children;
			$children = '<option value="">'.__('Please select...', $this->plugin->getDomain()).'</option>';
			foreach ($childrenArray as $value => $innerChildren) {
				$attrs = self::generateElementAttrs(['value' => $value]);
				$children .= "<option$attrs>$innerChildren</option>";
			}
		}

		return "<$tag$attrs>$children</$tag>";
	}
}
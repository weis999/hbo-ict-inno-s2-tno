<?php

namespace TNO\EssifLab\Presentation\Views;

use TNO\EssifLab\Contracts\Abstracts\ListView;
use TNO\EssifLab\Presentation\Components\Fieldset;

class ListWithCustomAdd extends ListView {
	public function __construct($pluginData, $args) {
		parent::__construct($pluginData, $args);

		$this->actions = [
			$this->getAddFieldset(),
		];
	}

	private function getAddFieldset(): Fieldset {
		$controls = [];

		foreach ($this->headings as $heading) {
			$controls[] = $this->getSelectFieldConfig($heading);
		}

		$controls[] = [
			'name' => 'action',
			'value' => 'add',
			'tag' => 'button',
			'class' => 'button',
			'children' => __('Add new', $this->getDomain()).' '.$this->subject,
		];

		return new Fieldset($this, [
			'baseName' => $this->baseName,
			'formControls' => $controls
		]);
	}

	private function getSelectFieldConfig($name): array {
		return [
			'placeholder' => __('Select', $this->getDomain()).' '.$name,
			'options' => array_key_exists($name, $this->options) ? $this->options[$name] : [],
			'name' => $name,
			'tag' => 'select',
		];
	}
}
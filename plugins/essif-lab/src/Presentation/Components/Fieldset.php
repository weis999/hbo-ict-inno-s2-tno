<?php

namespace TNO\EssifLab\Presentation\Components;

use TNO\EssifLab\Contracts\Abstracts\Component;
use TNO\EssifLab\Contracts\Interfaces\Core;

class Fieldset extends Component {
	private $baseName = '';

	/**
	 * @var callable[]
	 */
	private $renderers = [];

	/**
	 * @var FormControl[]
	 */
	private $formControls = [];

	public function __construct(Core $plugin, array $args = []) {
		parent::__construct($plugin);

		foreach ($args as $key => $value) {
			if (property_exists($this, $key) && gettype($this->{$key}) === gettype($value)) {
				$this->{$key} = $value;
			}
		}
	}

	public function render(): string {
		return $this->renderFormControls();
	}

	public function setBaseName(string $value) {
		$this->baseName = empty($this->baseName) ? $value : $this->baseName;
	}

	public function setRenderer(string $name, callable $callback) {
		$this->renderers[$name] = $callback;
	}

	protected function getRenderer(string $name): callable {
		if (array_key_exists($name, $this->renderers) && is_callable($this->renderers[$name])) {
			return $this->renderers[$name];
		} else {
			if ($name === 'container') {
				return function ($label, $input) {
					return $label.$input;
				};
			} else {
				return function ($widget) {
					return $widget;
				};
			}
		}
	}

	protected function renderFormControls(): string {
		$output = '';
		foreach ($this->getFormControls() as $formControl) {
			$label = $this->getRenderer('label')($formControl['label']);
			$input = $this->getRenderer('input')($formControl['input']);
			$output .= $this->getRenderer('container')($label, $input);
		}

		return $output;
	}

	protected function getFormControls(): array {
		$output = [];
		if (is_array($this->formControls) && count($this->formControls)) {
			foreach ($this->formControls as $formControl) {
				$output[] = (new FormControl($this->plugin, $this->useBaseName($formControl)))->getFormControl();
			}
		}

		return $output;
	}

	protected function useBaseName(array $formControl): array {
		if (array_key_exists('name', $formControl) && !empty($this->baseName)) {
			$formControl['name'] = $this->baseName.'['.$formControl['name'].']';
		}

		return $formControl;
	}
}
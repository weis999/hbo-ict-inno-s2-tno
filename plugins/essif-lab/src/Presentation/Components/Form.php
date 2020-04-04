<?php

namespace TNO\EssifLab\Presentation\Components;

use TNO\EssifLab\Contracts\Abstracts\Component;
use TNO\EssifLab\Contracts\Interfaces\Core;

class Form extends Component {
	/**
	 * @var FormControl[]
	 */
	private $controls = [];

	public function __construct(Core $plugin, $args = []) {
		parent::__construct($plugin);

		foreach ($args as $key => $value) {
			if (property_exists($this, $key) && gettype($this->{$key}) === gettype($value)) {
				$this->{$key} = $value;
			}
		}
	}

	public function render(): string {
		return $this->renderContainer();
	}

	protected function renderContainer() {
		return '<table class="form-table">'.$this->renderFormControls().'</table>';
	}

	protected function renderFormControls() {
		$output = '';
		if (is_array($this->controls) && count($this->controls)) {
			foreach ($this->controls as $control) {
				if (!empty($control)) {
					foreach ($control->getRenderedFields(true) as $field) {
						[$label, $input] = $field;
						$output .= $this->renderFormControl($label, $input);
					}
				}
			}
		}
		return $output;
	}

	protected function renderFormControl($label, $input) {
		$label = empty($label) ? '' : "<th>$label</th>";
		$input = empty($input) ? '' : "<td>$input</td>";
		return "<tr>$label$input</tr>";
	}

}
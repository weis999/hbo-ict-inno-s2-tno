<?php

namespace TNO\EssifLab\Presentation\Components;

use TNO\EssifLab\Contracts\Abstracts\Component;
use TNO\EssifLab\Contracts\Interfaces\Core;

class PostListActions extends Component {
	/**
	 * @var Fieldset[]
	 */
	private $fieldsets = [];

	private $location = 'top';

	private $align = 'left';

	private $children = '';

	public function __construct(Core $plugin, $args = []) {
		parent::__construct($plugin);

		foreach ($args as $key => $value) {
			if (property_exists($this, $key) && gettype($this->{$key}) === gettype($value)) {
				$this->{$key} = $value;
			}
		}
	}

	public function render(): string {
		$top = $this->location != 'bottom' ? $this->renderContainer('top') : '';
		$bottom = $this->location != 'top' ? $this->renderContainer('bottom') : '';
		return  $top . $this->children . $bottom;
	}

	protected function renderContainer($location) {
		return '<div class="tablenav '.$location.'">'.$this->renderFieldsets().'</div>';
	}

	protected function renderFieldsets() {
		$output = '';
		if (is_array($this->fieldsets) && count($this->fieldsets)) {
			foreach ($this->fieldsets as $fieldset) {
				if (!empty($fieldset)) {
					$output .= $this->renderFieldsetContainer($fieldset->render());
				}
			}
		}
		return $output;
	}

	protected function renderFieldsetContainer($children) {
		return '<div class="align'.$this->align.' actions">'.$children.'</div>';
	}

}
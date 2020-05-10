<?php

namespace TNO\EssifLab\Views\Items;

use TNO\EssifLab\Views\Items\Contracts\BaseItem;

class MultiDimensional extends BaseItem {
	protected $canDisplayValue = false;

	public function getValue() {
		if (is_array($this->value)) {
			return $this->value;
		}

		return [];
	}
}
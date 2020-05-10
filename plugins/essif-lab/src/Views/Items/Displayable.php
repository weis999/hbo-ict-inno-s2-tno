<?php

namespace TNO\EssifLab\Views\Items;

use TNO\EssifLab\Views\Items\Contracts\BaseItem;

class Displayable extends BaseItem {
	public function getValue() {
		if (is_numeric($this->value) || is_string($this->value)) {
			return $this->value;
		}

		return '';
	}
}
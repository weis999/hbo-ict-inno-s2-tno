<?php

namespace TNO\EssifLab\Contracts\Abstracts;

use TNO\EssifLab\Contracts\Interfaces\View as IView;

abstract class View extends Core implements IView {
	private $args = [];

	public function __construct($pluginData, $args) {
		parent::__construct($pluginData);
		$this->args = $args;
	}

	public function display(): void {
		print $this->render();
	}

	public function getArg($key) {
		return is_array($this->args) && array_key_exists($key, $this->args) ? $this->args[$key] : null;
	}
}
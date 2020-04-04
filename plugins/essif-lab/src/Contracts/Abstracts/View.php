<?php

namespace TNO\EssifLab\Contracts\Abstracts;

use TNO\EssifLab\Contracts\Interfaces\View as IView;

abstract class View extends Core implements IView {
	private $args = [];

	protected $name;

	public function __construct($pluginData, $args) {
		parent::__construct($pluginData);
		$this->args = $args;
		$this->name = $this->getArg('name', $this->getDefaultName());
	}

	public function display(): void {
		print $this->render();
	}

	public function getArg($key, $fallback = null) {
		return is_array($this->args) && array_key_exists($key, $this->args) ? $this->args[$key] : $fallback;
	}

	protected function getDefaultName() {
		return $this->getDomain().':'.substr(strrchr(get_class($this), '\\'), 1);
	}
}
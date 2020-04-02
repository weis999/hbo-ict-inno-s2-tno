<?php

namespace TNO\EssifLab\Abstracts;

use TNO\EssifLab\Interfaces\SimpleController as ISimpleController;

abstract class SimpleController extends Core implements ISimpleController {
	public function __construct($pluginData = []) {
		parent::__construct($pluginData);

		$this->execute();
	}
}
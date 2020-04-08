<?php

namespace TNO\EssifLab\Contracts\Abstracts;

use TNO\EssifLab\Contracts\Interfaces\SimpleController as ISimpleController;

abstract class SimpleController extends Core implements ISimpleController {
	public function __construct($pluginData = []) {
		parent::__construct($pluginData);

		$this->execute();
	}
}
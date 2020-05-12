<?php

namespace TNO\EssifLab\ModelManagers\Contracts;

use TNO\EssifLab\Applications\Contracts\Application;
use TNO\EssifLab\Utilities\Contracts\Utility;

abstract class BaseModelManager implements ModelManager {
	protected $application;

	protected $utility;

	function __construct(Application $application, Utility $utility) {
		$this->application = $application;
		$this->utility = $utility;
	}
}
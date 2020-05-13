<?php

namespace TNO\EssifLab\Utilities\Exceptions;

use Throwable;
use Exception;

class InvalidModelType extends Exception {
	public function __construct($className = "", $code = 0, Throwable $previous = null) {
		$message = "Invalid model type: '$className', unable to instantiate the given model type.";
		parent::__construct($message, $code, $previous);
	}
}
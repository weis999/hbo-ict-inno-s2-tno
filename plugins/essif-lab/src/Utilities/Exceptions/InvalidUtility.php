<?php

namespace TNO\EssifLab\Utilities\Exceptions;

use Exception;
use Throwable;

class InvalidUtility extends Exception {
	public function __construct($name = "", $code = 0, Throwable $previous = null) {
		$message = "Invalid utility, either the name '$name' is not a registered or the function is not callable.";
		parent::__construct($message, $code, $previous);
	}
}
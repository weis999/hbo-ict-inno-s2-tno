<?php

namespace TNO\EssifLab\Utilities\Contracts;

interface Utility {
	function __construct(array $functions = []);

	function call(string $name, ...$parameters);
}
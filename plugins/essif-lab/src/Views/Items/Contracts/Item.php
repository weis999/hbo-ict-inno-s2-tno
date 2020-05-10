<?php

namespace TNO\EssifLab\Views\Items\Contracts;

interface Item {
	function __construct($value, string $label = null);

	function getValue();

	function getLabel(): string;

	function canDisplayValue(): bool;
}
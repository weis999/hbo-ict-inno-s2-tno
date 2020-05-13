<?php

namespace TNO\EssifLab\Models\Contracts;

interface Model {
	function __construct($attrs = []);

	function getSingularName(): string;

	function getPluralName(): string;

	function getTypeName(): string;

	function getTypeArgs(): array;

	function getAttributes(): array;

	function getAttributeNames(): array;

	function getFields(): array;

	function getRelations(): array;
}
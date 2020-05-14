<?php

namespace TNO\EssifLab\Models\Contracts;

use TNO\EssifLab\Constants;

abstract class BaseModel implements Model {
	protected $singular;

	protected $plural;

	protected $attributes;

	protected $attributeNames;

	protected $fields;

	protected $relations;

	protected $typeArgs;

	public function __construct($attrs = []) {
		$this->setAttributes($attrs);
	}

	public function getSingularName(): string {
		if (empty($this->singular)) {
			return substr($this->getPluralName(), 0, Constants::TYPE_DEFAULT_SINGULAR_SUBSTR_LENGTH);
		}
		return trim(strtolower($this->singular));
	}

	public function getPluralName(): string {
		if (empty($this->plural)) {
			return $this->getSingularName() . Constants::TYPE_DEFAULT_PLURAL_SUFFIX;
		}
		return trim(strtolower($this->plural));
	}

	public function getTypeName(): string {
		$str = preg_replace('/[\s_]/', Constants::TYPE_NAME_SEPARATOR, $this->getSingularName());
		if (strlen($str) > Constants::TYPE_NAME_MAX_LENGTH) {
			$str = preg_replace('/[aeiou]/', '', $str);
		}

		return $str;
	}

	public function getTypeArgs(): array {
		return array_merge(Constants::TYPE_DEFAULT_TYPE_ARGS, self::isArray($this->typeArgs));
	}

	public function getAttributes(): array {
		return self::isArray($this->attributes);
	}

	public function setAttributes($value): void {
		$this->attributes = array_filter(self::isArray($value), function ($key) {
			return in_array($key, $this->getAttributeNames());
		}, ARRAY_FILTER_USE_KEY);
	}

	public function getAttributeNames(): array {
		return array_merge(Constants::TYPE_DEFAULT_ATTRIBUTE_NAMES, self::isArray($this->attributeNames));
	}

	public function getFields(): array {
		return array_merge(Constants::TYPE_DEFAULT_FIELDS, self::isArray($this->fields));
	}

	public function getRelations(): array {
		return self::isArray($this->relations);
	}

	private static function isArray($value): array {
		return is_array($value) ? $value : [];
	}
}
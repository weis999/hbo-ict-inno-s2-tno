<?php

namespace TNO\EssifLab\Utilities\Contracts;

use TNO\EssifLab\Utilities\Exceptions\InvalidUtility;

abstract class BaseUtility implements Utility {
	public const CREATE_MODEL = 'createModel';

	public const GET_MODELS = 'getModels';

	public const DELETE_MODEL = 'deleteModel';

	public const CREATE_MODEL_TYPE = 'createModelType';

	public const CREATE_MODEL_META = 'createModelMeta';

	public const GET_MODEL_META = 'getModelMeta';

	public const DELETE_MODEL_META = 'deleteModelMeta';

	public const GET_EDIT_MODEL_LINK = 'getEditModelLink';

	public const GET_CREATE_MODEL_LINK = 'getCreateModelLink';

	protected $functions = [];

	function __construct(array $functions = []) {
		$this->functions = array_merge([
			self::CREATE_MODEL_TYPE => [static::class, 'createModelType'],
			self::CREATE_MODEL => [static::class, 'createModel'],
			self::DELETE_MODEL => [static::class, 'deleteModel'],
			self::GET_MODELS => [static::class, 'getModels'],
			self::CREATE_MODEL_META => [static::class, 'createModelMeta'],
			self::DELETE_MODEL_META => [static::class, 'deleteModelMeta'],
			self::GET_MODEL_META => [static::class, 'getModelMeta'],
			self::GET_EDIT_MODEL_LINK => [static::class, 'getEditModelLink'],
			self::GET_CREATE_MODEL_LINK => [static::class, 'getCreateModelLink'],
		], $this->functions, $functions);
	}

	function call(string $name, ...$parameters) {
		$function = $this->getFunctionByName($name);

		return $function(...$parameters);
	}

	private function getFunctionByName(string $name): callable {
		if ($this->isValidFunction($name)) {
			throw new InvalidUtility($name);
		}

		return $this->functions[$name];
	}

	private function isValidFunction(string $name): bool {
		return ! is_array($this->functions) || ! array_key_exists($name, $this->functions) || ! is_callable($this->functions[$name]);
	}
}
<?php

namespace TNO\EssifLab\Integrations\Contracts;

use HaydenPierce\ClassFinder\ClassFinder;
use TNO\EssifLab\Applications\Contracts\Application;
use TNO\EssifLab\Constants;
use TNO\EssifLab\ModelManagers\Contracts\ModelManager;
use TNO\EssifLab\Models\Contracts\Model;

abstract class BaseIntegration implements Integration {
	public const REGISTER_TYPE = 'register_type';

	public const REGISTER_RELATION = 'register_relation';

	public const GET_ADD_TYPE_LINK = 'get_add_type_link';

	public const GET_EDIT_TYPE_LINK = 'get_edit_type_link';

	protected $application;

	protected $manager;

	protected $utilities = [];

	function __construct(Application $application, ModelManager $manager, array $utilities = []) {
		$this->application = $application;
		$this->manager = $manager;
		$this->utilities = array_merge($this->utilities, $utilities);
	}

	function useUtility(string $name, ...$parameters) {
		$utility = is_array($this->utilities) && array_key_exists($name, $this->utilities) ? $this->utilities[$name] : null;

		if (empty($utility)) {
			return null;
		}

		if (is_array($utility)) {
			return call_user_func_array($utility, ...$parameters);
		}

		return call_user_func($utility, ...$parameters);
	}

	function getApplication(): Application {
		return $this->application;
	}

	protected static function forAllModels(callable $callback): void {
		$classNames = ClassFinder::getClassesInNamespace(Constants::TYPE_NAMESPACE);
		if (! empty($classNames)) {
			foreach ($classNames as $className) {
				if (self::isConcreteModel($className)) {
					$instance = new $className();
					$callback($instance);
				}
			}
		}
	}

	protected static function forEachModel(array $classNames, callable $callback): void {
		if (! empty($classNames)) {
			foreach ($classNames as $className) {
				if (self::isConcreteModel($className)) {
					$instance = new $className();
					$callback($instance);
				}
			}
		}
	}

	protected  static function isConcreteModel(string $class): bool {
		return class_exists($class) && in_array(Model::class, class_implements($class));
	}
}
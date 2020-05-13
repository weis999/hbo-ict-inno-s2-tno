<?php

namespace TNO\EssifLab\Tests\Stubs;

use TNO\EssifLab\Utilities\Contracts\BaseUtility;
use TNO\EssifLab\Utilities\WordPress;

class Utility extends BaseUtility {
	private $history = [];

	protected $exceptionalFunctions = [
		WordPress::ADD_ACTION => [self::class, 'addHook'],
		WordPress::ADD_FILTER => [self::class, 'addHook'],
	];

	function call(string $name, ...$parameters) {
		if (array_key_exists($name, $this->exceptionalFunctions)) {
			$callback = $this->exceptionalFunctions[$name];
			$callback(...$parameters);
		}

		$wasCalled = count($this->getHistoryByFuncName($name));
		$this->history[] = new History($name, $parameters, $wasCalled + 1);
	}

	/**
	 * @param string $funcName
	 * @return History[]
	 */
	function getHistoryByFuncName(string $funcName): array {
		return array_filter($this->history, function (History $history) use ($funcName) {
			return $history->getFuncName() === $funcName;
		});
	}

	static function addHook(string $hook, callable $callback, int $priority = 10, int $accepted_args = 1): void {
		$params = range(0, $accepted_args);
		$callback(...$params);
	}
}
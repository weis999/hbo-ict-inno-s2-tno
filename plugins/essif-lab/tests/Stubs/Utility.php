<?php

namespace TNO\EssifLab\Tests\Stubs;

use TNO\EssifLab\Utilities\Contracts\BaseUtility;

class Utility extends BaseUtility {
	private $history = [];

	function call(string $name, ...$parameters) {
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
}
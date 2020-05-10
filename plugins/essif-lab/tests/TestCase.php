<?php

namespace TNO\EssifLab\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use TNO\EssifLab\Constants;
use TNO\EssifLab\Tests\Stubs\Application;
use TNO\EssifLab\Tests\Stubs\Integration;
use TNO\EssifLab\Tests\Stubs\Model;
use TNO\EssifLab\Tests\Stubs\ModelManager;

abstract class TestCase extends PHPUnitTestCase {
	const TIMES_CALLED = 'times_called';

	const LAST_CALL_WITH = 'last_call_with';

	protected $application;

	protected $manager;

	protected $integration;

	protected $model;

	protected $was_called = [];

	protected function setUp(): void {
		parent::setUp();
		if (! defined('ABSPATH')) {
			define('ABSPATH', __DIR__);
		};
		$this->was_called = [];
		$this->application = new Application();
		$this->manager = new ModelManager($this->application);
		$this->integration = new Integration($this->application, $this->manager);
		$this->model = new Model([
			Constants::TYPE_INSTANCE_TITLE_ATTR => 'hello',
			Constants::TYPE_INSTANCE_DESCRIPTION_ATTR => 'world'
		]);
	}

	protected function callStubFunc(string $name, ...$params) {
		if (array_key_exists($name, $this->was_called)) {
			$this->was_called[$name][self::TIMES_CALLED] += 1;
		} else {
			$this->was_called[$name][self::TIMES_CALLED] = 1;
		}
		$this->was_called[$name][self::LAST_CALL_WITH] = $params;
	}
}
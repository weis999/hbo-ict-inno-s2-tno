<?php

namespace TNO\EssifLab\Tests\Stubs;

class Application implements \TNO\EssifLab\Applications\Contracts\Application {
	function getName(): string {
		return 'name';
	}

	function getNamespace(): string {
		return 'namespace';
	}

	function getAppDir(): string {
		return 'appDir';
	}
}
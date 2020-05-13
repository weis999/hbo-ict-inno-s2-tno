<?php

namespace TNO\EssifLab\Applications;

use TNO\EssifLab\Applications\Contracts\Application;

class Plugin implements Application {
	private $name;

	private $namespace;

	private $appDirectory;

	public function __construct($name, $namespace, $appDirectory) {
		$this->name = $name;
		$this->namespace = $namespace;
		$this->appDirectory = $appDirectory;
	}

	public function getName(): string {
		return $this->name;
	}

	public function getNamespace(): string {
		return $this->namespace;
	}

	public function getAppDir(): string {
		return $this->appDirectory;
	}
}
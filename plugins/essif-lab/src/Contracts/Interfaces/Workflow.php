<?php

namespace TNO\EssifLab\Contracts\Interfaces;

interface Workflow {
	public static function register(Core $pluginData, $post): void;

	public static function getActionName(): string;

	public function execute(array $request): void;
}
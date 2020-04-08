<?php

namespace TNO\EssifLab\Contracts\Interfaces;

interface Workflow {
	public static function register(Core $core, $post, $key): void;

	public static function options(): array;

	public function execute(array $request): void;
}
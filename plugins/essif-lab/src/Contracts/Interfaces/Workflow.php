<?php

namespace TNO\EssifLab\Contracts\Interfaces;

interface Workflow {
	public function execute(array $request): void;
}
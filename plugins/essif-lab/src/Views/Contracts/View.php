<?php

namespace TNO\EssifLab\Views\Contracts;

use TNO\EssifLab\Integrations\Contracts\Integration;
use TNO\EssifLab\Models\Contracts\Model;

interface View {
	function __construct(Integration $integration, Model $model, array $items = []);

	function render(): string;
}
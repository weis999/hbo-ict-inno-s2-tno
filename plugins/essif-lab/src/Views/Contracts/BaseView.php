<?php

namespace TNO\EssifLab\Views\Contracts;

use TNO\EssifLab\Integrations\Contracts\Integration;
use TNO\EssifLab\Models\Contracts\Model;
use TNO\EssifLab\Views\Items\Contracts\Item;

abstract class BaseView implements View {
	protected $integration;

	protected $model;

	protected $items;

	public function __construct(Integration $integration, Model $model, array $items = []) {
		$this->integration = $integration;
		$this->model = $model;
		$this->items = $items;
	}

	protected function getDisplayableItems(): array {
		return array_filter($this->items, function (Item $item) {
			return $item->canDisplayValue() && !empty($item->getValue());
		});
	}

	protected function getNonDisplayableItems(): array {
		return array_filter($this->items, function (Item $item) {
			return !$item->canDisplayValue() && !empty($item->getValue());
		});
	}
}
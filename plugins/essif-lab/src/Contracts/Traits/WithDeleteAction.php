<?php

namespace TNO\EssifLab\Contracts\Traits;

use TNO\EssifLab\Presentation\Components\Fieldset;

trait WithDeleteAction {
	public function getDeleteAction(): callable  {
		return function ($item) {
			return $this->getDeleteFieldset($item)->render();
		};
	}

	private function getDeleteFieldset($item): Fieldset {
		$key = 'ID';
		$value = array_key_exists($key, $item) ? $item[$key] : '';
		return new Fieldset($this, [
			'baseName' => $this->baseName,
			'formControls' => [
				[
					'name' => $key,
					'value' => $value,
					'type' => 'hidden',
				],
				[
					'tag' => 'button',
					'name' => 'action',
					'value' => 'delete',
					'class' => 'button-link',
					'children' => __('Delete', $this->getDomain()),
				],
			],
		]);
	}
}
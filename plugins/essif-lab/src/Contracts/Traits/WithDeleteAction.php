<?php

namespace TNO\EssifLab\Contracts\Traits;

use TNO\EssifLab\Presentation\Components\Fieldset;

trait WithDeleteAction {
	public function getDeleteAction(): callable  {
		return function ($item) {
			return $this->getDeleteFieldset($item)->render();
		};
	}

	private function getDeleteFieldset($id): Fieldset {
		return new Fieldset($this, [
			'baseName' => $this->baseName,
			'formControls' => [
				[
					'name' => 'id',
					'value' => $id,
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
<?php

namespace TNO\EssifLab\Contracts\Traits;

use TNO\EssifLab\Presentation\Components\Fieldset;

trait WithDeleteAction {
	public function getDeleteAction(): callable  {
		return function ($item) {
            var_dump("item", $item);
            die();
			return $this->getDeleteFieldset($item)->render();
		};
	}

	private function getDeleteFieldset($id): Fieldset {
//        var_dump("id", $id);
//        die();
		return new Fieldset($this, [
			'baseName' => $this->baseName,
			'formControls' => [
				[
					'name' => $id,
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
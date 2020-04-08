<?php

namespace TNO\EssifLab\Contracts\Traits;

use TNO\EssifLab\Presentation\Components\Fieldset;

trait WithUseAction {
	private function getUseAction($singular, $options = []): Fieldset {
		return new Fieldset($this, [
			'baseName' => $this->baseName,
			'formControls' => [
				[
					'tag' => 'select',
					'name' => 'id',
					'options' => $options,
					'placeholder' => __('Select', $this->getDomain()).' '.$singular,
				],
				[
					'tag' => 'button',
					'name' => 'action',
					'value' => 'add',
					'class' => 'button',
					'children' => __('Use', $this->getDomain()).' '.$singular,
				],
			],
		]);
	}
}
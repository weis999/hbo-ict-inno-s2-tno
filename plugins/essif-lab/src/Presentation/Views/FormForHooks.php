<?php

namespace TNO\EssifLab\Presentation\Views;

use TNO\EssifLab\Contracts\Abstracts\View;
use TNO\EssifLab\Presentation\Components\Form;
use TNO\EssifLab\Presentation\Components\FormControl;
use TNO\EssifLab\Presentation\Components\PostList;

class FormForHooks extends View {
	private $contexts = [];

	private $targets = [];

	public function __construct($pluginData, $args) {
		parent::__construct($pluginData, $args);

		$this->contexts = $this->getArg('contexts', $this->contexts);
		$this->targets = $this->getArg('targets', $this->targets);
	}

	public function render(): string {
		return $this->getForm()->render();
	}

	private function getForm(): Form {
		return new Form($this, [
			'controls' => [$this->getAddFormControl()],
		]);
	}

	private function getAddFormControl(): FormControl {
		return new FormControl($this, [
			'name' => $this->name,
			'fields' => [
				[
					'label' => __('Select a context', $this->getDomain()),
					'children' => $this->contexts,
					'name' => 'context',
					'type' => 'select',
				],
				[
					'label' => __('Select a target', $this->getDomain()),
					'children' => $this->targets,
					'name' => 'target',
					'type' => 'select',
				],
				[
					'children' => __('Add new hook', $this->getDomain()),
					'name' => 'action',
					'value' => 'add',
					'type' => 'button',
					'class' => 'button',
				],
			],
		]);
	}
}
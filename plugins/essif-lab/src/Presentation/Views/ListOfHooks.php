<?php

namespace TNO\EssifLab\Presentation\Views;

use TNO\EssifLab\Contracts\Abstracts\View;
use TNO\EssifLab\Presentation\Components\FormControl;
use TNO\EssifLab\Presentation\Components\PostList;

class ListOfHooks extends View {
	private $headings = ['context', 'target'];

	private $hooks = [];

	public function __construct($pluginData, $args) {
		parent::__construct($pluginData, $args);

		$this->headings = $this->getArg('headings', $this->headings);
		$this->hooks = $this->getArg('items', $this->hooks);
	}

	public function render(): string {
		return $this->getPostList()->render();
	}

	private function getPostList(): PostList {
		$delete = function ($id) {
			return $this->getDeleteFormControl($id)->render();
		};

		return new PostList($this, [
			'headings' => $this->headings,
			'items' => $this->hooks,
			'itemActions' => [$delete],
		]);
	}

	private function getDeleteFormControl($id): FormControl {
		return new FormControl($this, [
			'name' => $this->name,
			'fields' => [
				[
					'name' => 'id',
					'value' => $id,
					'type' => 'hidden',
				],
				[
					'children' => __('Delete', $this->getDomain()),
					'name' => 'action',
					'value' => 'delete',
					'type' => 'button',
					'class' => 'button-link',
				],
			],
		]);
	}
}
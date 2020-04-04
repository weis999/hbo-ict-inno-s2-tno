<?php

namespace TNO\EssifLab\Presentation\Views;

use TNO\EssifLab\Contracts\Abstracts\View;
use TNO\EssifLab\Presentation\Components\PostList;

class Hooks extends View {
	private $items = [];

	public function __construct($pluginData, $args) {
		parent::__construct($pluginData, $args);

		$this->items = $this->getArg('items');
	}

	public function render(): string {
		global $post;

		$list = new PostList($this, [
			'headings' => [
				'context',
				'target',
			],
			'items' => $this->items,
			'itemActions' => [
				function ($id) {
					$name = $this->getDomain().':hooks';
					$value = json_encode(['action' => 'delete', 'payload' => $id]);
					return '<button type="submit" class="button-link" name="'.$name.'" value="'.$value.'">'.__('Delete').'</button>';
				}
			],
		]);

		return $list->render();
	}
}
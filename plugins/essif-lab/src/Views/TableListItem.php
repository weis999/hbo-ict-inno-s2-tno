<?php

namespace TNO\EssifLab\Views;

use TNO\EssifLab\Views\Contracts\BaseView;
use TNO\EssifLab\Views\Items\Contracts\Item;

class TableListItem extends BaseView {
	const PRIMARY_COL_CLASSES = [
		'column-title',
		'column-primary',
		'has-row-actions',
	];

	function render(): string {
		$first = true;
		$classes = implode(' ', self::PRIMARY_COL_CLASSES);

		return implode('', array_map(function (Item $item) use (&$first, $classes) {
			if ($first) {
				$first = false;
				$itemForm = $this->renderItemForm($item);

				return '<td class="'.$classes.'"><strong>'.$item->getLabel().'</strong>'.$itemForm.'</td>';
			}

			return '<td>'.$item->getLabel().'</td>';
		}, $this->getDisplayableItems()));
	}

	private function renderItemForm(Item $item) {
		$instance = new ListItemForm($this->integration, $this->model, [$item]);

		return '<div class="row-actions">'.$instance->render().'</div>';
	}
}
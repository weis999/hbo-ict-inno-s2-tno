<?php

namespace TNO\EssifLab\Views;

use TNO\EssifLab\Constants;
use TNO\EssifLab\Integrations\Contracts\BaseIntegration;
use TNO\EssifLab\Utilities\Contracts\BaseUtility;
use TNO\EssifLab\Views\Contracts\BaseView;
use TNO\EssifLab\Views\Items\Contracts\Item;

class ListItemForm extends BaseView {
	const REMOVE = 'Remove item';

	const EDIT = 'Edit';

	function render(): string {
		$items = $this->getDisplayableItems();
		if (empty($items) || ! is_array($items)) {
			return '';
		}

		$item = current($items);

		return $this->renderRemove($item).' | '.$this->renderEdit($item);
	}

	private function renderRemove(Item $item) {
		$name = $this->integration->getApplication()->getNamespace().'['.Constants::ACTION_NAME_REMOVE_RELATION.']';

		return '<button name="'.$name.'" value="'.$item->getValue().'" class="button-link">'.self::REMOVE.'</button>';
	}

	private function renderEdit(Item $item) {
		$url = $this->integration->getUtility()->call(BaseUtility::GET_EDIT_MODEL_LINK, $item->getValue());
		$title = self::EDIT.' '.$item->getLabel();

		return '<a href="'.$url.'" title="'.$title.'" class="button-link">'.self::EDIT.'</a>';
	}
}
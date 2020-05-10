<?php

namespace TNO\EssifLab\Views;

use TNO\EssifLab\Integrations\Contracts\Integration;
use TNO\EssifLab\Models\Contracts\Model;
use TNO\EssifLab\Views\Contracts\BaseView;
use TNO\EssifLab\Views\Contracts\View;
use TNO\EssifLab\Views\Items\Contracts\BaseItem;

class TypeList extends BaseView {
	const FORM_ITEMS = 'form_items';

	const LIST_ITEMS = 'list_items';

	private $form;

	private $list;

	public function __construct(Integration $integration, Model $model, array $items = []) {
		parent::__construct($integration, $model, $items);
		$this->form = new ListForm($this->integration, $this->model, $this->getChildItems(self::FORM_ITEMS));
		$this->list = new TableList($this->integration, $this->model, $this->getChildItems(self::LIST_ITEMS));
	}

	function setForm(View $view): void {
		$this->form = $view;
	}

	function setList(View $view): void {
		$this->list = $view;
	}

	function render(): string {
		return $this->renderForm().$this->renderList();
	}

	private function renderList(): string {
		return '<div class="list">'.$this->list->render().'</div>';
	}

	private function renderForm(): string {
		return '<div class="form">'.$this->form->render().'</div>';
	}

	private function getChildItems(string $key): array {
		$item = BaseItem::getByLabel($this->getNonDisplayableItems(), $key);
		if (empty($item) || ! is_array($item->getValue())) {
			return [];
		}

		return $item->getValue();
	}
}
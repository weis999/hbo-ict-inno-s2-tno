<?php

namespace TNO\EssifLab\Views;

use TNO\EssifLab\Constants;
use TNO\EssifLab\Views\Contracts\BaseView;
use TNO\EssifLab\Views\Items\Contracts\Item;

class TableList extends BaseView {
	const NO_RESULTS = 'No %s found.';

	public function render(): string {
		$children = $this->renderHeader().$this->renderBody().$this->renderFooter();

		return '<table class="wp-list-table widefat striped">'.$children.'</table>';
	}

	private function renderHeader(): string {
		return '<thead>'.$this->renderHeadings().'</thead>';
	}

	private function renderFooter(): string {
		return '<tfoot>'.$this->renderHeadings().'</tfoot>';
	}

	private function renderHeadings(): string {
		$headings = implode('', array_map(function ($heading) {
			return '<th>'.ucfirst($heading).'</th>';
		}, Constants::TYPE_LIST_DEFAULT_HEADINGS));

		return "<tr>$headings</tr>";
	}

	private function renderBody(): string {
		return '<tbody>'.$this->renderItems().'</tbody>';
	}

	private function renderItems(): string {
		$items = $this->getNonDisplayableItems();
		if (empty($items)) {
			return $this->renderNoResultsMessage();
		}

		return implode('', array_filter(array_map(function (Item $item) {
			$listItem = new TableListItem($this->integration, $this->model, $item->getValue());

			return '<tr>'.$listItem->render().'</tr>';
		}, $items)));
	}

	private function renderNoResultsMessage(): string {
		$message = sprintf(self::NO_RESULTS, $this->model->getPluralName());

		return '<tr class="no-items"><td colspan="99">'.$message.'</td></tr>';
	}
}
<?php

namespace TNO\EssifLab\Presentation\Components;

use TNO\EssifLab\Contracts\Abstracts\Component;
use TNO\EssifLab\Contracts\Interfaces\Core;

class PostList extends Component {
	private $idAttr = 'id';

	private $headings = [];

	private $items = [];

	private $itemActions = [];

	public function __construct(Core $plugin, $args = []) {
		parent::__construct($plugin);

		foreach ($args as $key => $value) {
			if (property_exists($this, $key) && gettype($this->{$key}) === gettype($value)) {
				$this->{$key} = $value;
			}
		}
	}

	public function render(): string {
		$children = $this->renderHeader().$this->renderBody().$this->renderFooter();

		return '<table class="wp-list-table widefat striped">'.$children.'</table>';
	}

	protected function renderHeader(): string {
		return '<thead>'.$this->renderHeadings().'</thead>';
	}

	protected function renderFooter(): string {
		return '<tfoot>'.$this->renderHeadings().'</tfoot>';
	}

	protected function renderHeadings(): string {
		$output = '';
		if (is_array($this->headings)) {
			foreach ($this->headings as $heading) {
				$output .= '<th>'.ucfirst($heading).'</th>';
			}
		}

		return "<tr>$output</tr>";
	}

	protected function renderBody(): string {
		return '<tbody>'.$this->renderItems().'</tbody>';
	}

	protected function renderItems(): string {
		$output = '';
		if (is_array($this->items) && count($this->items)) {
			foreach ($this->items as $item) {
				$output .= '<tr>'.$this->renderAttributes($item).'</tr>';
			}
		} else {
			$output = '<tr class="no-items"><td colspan="99">'.__('No posts found.').'</td></tr>';
		}

		return $output;
	}

	protected function renderAttributes(array $item): string {
		$output = '';
		if (is_array($item)) {
			$first = true;
			foreach ($this->headings as $heading) {
				$attr = array_key_exists($heading, $item) ? $item[$heading] : '-';
				$classes = [];
				$after = '';
				if ($first) {
					$first = false;
					$classes = array_merge($classes, ['column-title', 'column-primary']);
					$attr = "<strong>$attr</strong>";
					if (count($this->itemActions)) {
						$id = array_key_exists($this->idAttr, $item) ? $item[$this->idAttr] : null;
						$classes = array_merge($classes, ['has-row-actions']);
						$after .= $this->renderItemActions($id);
					}
				}
				$attrs = self::generateElementAttrs(['class' => self::generateClasses($classes)]);
				$output .= "<td".$attrs.">$attr$after</td>";
			}
		}

		return $output;
	}

	protected function renderItemActions($id) {
		$actions = '';
		$first = true;
		foreach ($this->itemActions as $component) {
			$prefix = ' | ';
			if ($first) {
				$first = false;
				$prefix = '';
			}
			$actions .= '<span>'.$prefix.$component($id).'</span>';
		}

		return '<div class="row-actions">'.$actions.'</div>';
	}
}
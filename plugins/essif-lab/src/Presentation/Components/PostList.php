<?php

namespace TNO\EssifLab\Presentation\Components;

use TNO\EssifLab\Contracts\Abstracts\Component;
use TNO\EssifLab\Contracts\Interfaces\Core;

class PostList extends Component {
	private $viewable = false;

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
				$output .= '<tr>'.$this->renderItemAttributes($item).'</tr>';
			}
		} else {
			$output = '<tr class="no-items"><td colspan="99">'.__('No posts found.').'</td></tr>';
		}

		return $output;
	}

	protected function renderItemAttributes(array $item): string {
		$output = '';
		if (is_array($item)) {
			$first = true;
			foreach ($this->headings as $heading) {
				$output .= $this->renderItemAttribute($item, $heading, $first);
			}
		}

		return $output;
	}

	protected function renderItemAttribute(array $item, string $heading, bool &$first): string {
		$value = array_key_exists($heading, $item) ? $item[$heading] : '-';
		$classes = [];
		if ($first) {
			$first = false;
			$value = $this->getPrimaryItemAttribute($value, $item, $classes);
		}
		$attrs = self::generateElementAttrs(['class' => self::generateClasses($classes)]);

		return "<td".$attrs.">$value</td>";
	}

	protected function getPrimaryItemAttribute($value, $item, &$classes = []): string {
		$classes = array_merge($classes, ['column-title', 'column-primary']);
		$value = "<strong>$value</strong>";
		$value = $this->includeEditPostLink($value, $item);

		return $this->includeItemActions($value, $item);
	}

	protected function includeEditPostLink($value, $item): string {
		$id = array_key_exists($this->idAttr, $item) ? $item[$this->idAttr] : null;

		return ! empty($id) && $this->viewable ? '<a href="'.get_edit_post_link($id).'">'.$value.'</a>' : $value;
	}

	protected function includeItemActions($value, $item, &$classes = []): string {
		if (count($this->itemActions)) {
			$classes = array_merge($classes, ['has-row-actions']);

			return $value.$this->renderItemActions($item);
		}

		return $value;
	}

	protected function renderItemActions($item) {
		$actions = '';
		$first = true;
		foreach ($this->itemActions as $component) {
			$prefix = ' | ';
			if ($first) {
				$first = false;
				$prefix = '';
			}
			$actions .= '<span>'.$prefix.$component($item).'</span>';
		}

		return '<div class="row-actions">'.$actions.'</div>';
	}
}
<?php

namespace TNO\EssifLab\Contracts\Abstracts;

use TNO\EssifLab\Contracts\Traits\WithDeleteAction;
use TNO\EssifLab\Presentation\Components\Fieldset;
use TNO\EssifLab\Presentation\Components\PostList;
use TNO\EssifLab\Presentation\Components\PostListActions;

abstract class ListView extends View {
	use WithDeleteAction;

	/**
	 * The name of the post type of what this view is about.
	 *
	 * @var string
	 */
	protected $subject;

	/**
	 * What to use as the "base" name for each input its `name` attribute.
	 *
	 * @var string
	 */
	protected $baseName = '';

	/**
	 * The labels of the headings to display in the list.
	 *
	 * _*Note*: these labels should match the keys in the `$items` array._
	 *
	 * @var string[]
	 */
	protected $headings = [];

	/**
	 * The actions what apply to a single item in list
	 *
	 * @var Fieldset[]
	 */
	protected $itemActions = [];

	/**
	 * The actions what apply to the whole list
	 *
	 * @var Fieldset[]
	 */
	protected $actions = [];

	/**
	 * An associative array, keyed by the post type/model name what again contains an associative array with the actual
	 * key value pair to load into the specified select list.
	 *
	 * @var array[]
	 */
	protected $options = [];

	/**
	 * The items to show in the list. An array with an associative array keyed by headings and additional items such as
	 * an item for the 'ID'.
	 *
	 * @var array[]
	 */
	protected $items = [];

	public function __construct($pluginData, $args) {
		parent::__construct($pluginData, $args);

		$this->subject = $this->getArg('subject', $this->subject);
		$this->baseName = $this->getArg('baseName', $this->baseName);
		$this->headings = $this->getArg('headings', $this->headings);
		$this->itemActions = $this->getArg('itemActions', [$this->getDeleteAction()]);
		$this->actions = $this->getArg('actions', $this->actions);
		$this->options = $this->getArg('options', $this->options);
		$this->items = $this->getArg($this->subject, $this->items);
	}

	public function render(): string {
		return $this->getPostListActions()->render();
	}

	private function getPostListActions(): PostListActions {
		return new PostListActions($this, [
			'baseName' => $this->baseName,
			'fieldsets' => $this->actions,
			'children' => $this->getPostList()->render()
		]);
	}

	private function getPostList(): PostList {
		$isPostType = post_type_exists($this->subject);

		return new PostList($this, [
			'viewable' => $isPostType,
			'headings' => $this->headings,
			'items' => $this->items,
			'itemActions' => $this->itemActions,
		]);
	}

}
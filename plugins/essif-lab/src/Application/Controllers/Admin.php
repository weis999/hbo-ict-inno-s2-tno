<?php

namespace TNO\EssifLab\Application\Controllers;

defined('ABSPATH') or die();

use TNO\EssifLab\Application\Workflows\ManageHooks;
use TNO\EssifLab\Contracts\Abstracts\Controller;
use TNO\EssifLab\Contracts\Interfaces\RegistersPostTypes;
use TNO\EssifLab\Presentation\Views\FormForHooks;
use TNO\EssifLab\Presentation\Views\ListOfHooks;

class Admin extends Controller implements RegistersPostTypes {
	private $postTypes = [
		'validation-policy',
		'issuer',
		'schema',
	];

	public function getActions(): array {
		$this->addAction('init', $this, 'registerPostTypes');
		$this->addAction('admin_menu', $this, 'registerAdminMenuItem');
		$this->addAction('add_meta_boxes', $this, 'addMetaBoxes');
		$this->addAction('save_post_validation-policy', $this, 'registerHooksWorkflows');

		return $this->actions;
	}

	public function getFilters(): array {
		return $this->filters;
	}

	public function registerAdminMenuItem(): void {
		add_menu_page($this->getName(), $this->getName(), 'manage_options', $this->getDomain(), null, 'dashicons-lock');
	}

	public function registerPostTypes(): void {
		foreach ($this->postTypes as $postType) {
			$this->registerPostType($postType);
		}
	}

	private function registerPostType($name): void {
		$singular = ucfirst(str_replace('-', ' ', $name));
		$plural = $this->getPluralFromSingular($singular);
		register_post_type($name, [
			'labels' => [
				'name' => $plural,
				'singular_name' => $singular,
			],
			'supports' => ['title'],
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => $this->getDomain(),
		]);
	}

	public function addMetaBoxes(): void {
		$hooksForm = new FormForHooks($this->getPluginData(), json_decode(get_post()->post_content, true));
		$hooksList = new ListOfHooks($this->getPluginData(), json_decode(get_post()->post_content, true));
		add_meta_box('validation-policy-form-for-hooks', 'Form for Hooks', [$hooksForm, 'display'], 'validation-policy', 'normal');
		add_meta_box('validation-policy-list-of-hooks', 'List of Hooks', [$hooksList, 'display'], 'validation-policy', 'normal');
	}

	private function getPluralFromSingular($str): string {
		switch (substr($str, -1)) {
			case 'y':
				$str = substr($str, 0, -1).'ies';
				break;
			case 'f':
				$str = substr($str, 0, -1).'ves';
				break;
			case 's':
				$str = $str.'es';
				break;
			default:
				$str = $str.'s';
				break;
		}

		return $str;
	}

	public function registerHooksWorkflows($post_id, $post): void {
		$key = $this->getDomain().':hooks';
		if (is_array($_POST) && array_key_exists($key, $_POST)) {
			$request = json_decode($_POST[$key], true);
			$workflow = new ManageHooks($this->getPluginData(), [$post_id, $post]);
			$workflow->execute($request);
		}
	}
}
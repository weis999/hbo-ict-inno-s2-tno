<?php

namespace TNO\EssifLab\Application\Controllers;

defined('ABSPATH') or die();

use TNO\EssifLab\Application\Workflows\ManageCredentials;
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
		$this->addAction('save_post_'.$this->postTypes[0], $this, 'registerValidationPolicyWorkflows', 10, 2);

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
		$data = $this->getPluginData();

		$this->addHooksMetaBox($data);
	}

	private function getPostContentAsJson($post = null) {
		$post_content = 'post_content';
		$post = empty($post) ? get_post() : $post;
		$content = is_array($post) && array_key_exists($post_content, $post) ? $post[$post_content] : null;
		$content = empty($content) && is_object($post) && property_exists($post, $post_content) ? $post->{$post_content} : $content;
		$content = json_decode($content, true);

		return empty($content) || ! is_array($content) ? [] : $content;
	}

	private function addHooksMetaBox($data): void {
		$args = array_merge($this->getPostContentAsJson(), [
			'name' => ManageHooks::getActionName(),
		]);
		$this->addMetaBox($this->postTypes[0], 'Form for Hooks', new FormForHooks($data, $args));
		$this->addMetaBox($this->postTypes[0], 'List of Hooks', new ListOfHooks($data, $args));
	}

	private function addCredentialMetaBox($data): void {
		$args = array_merge($this->getPostContentAsJson(), [
			'name' => ManageCredentials::getActionName(),
		]);
		$this->addMetaBox($this->postTypes[0], 'List of Hooks', new ListOfHooks($data, $args));
	}

	private function addMetaBox($screen, $title, $component): void {
		$name = strtolower(str_replace(' ', '-', $title));
		add_meta_box("$screen-$name", $title, [$component, 'display'], $screen, 'normal');
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

	public function registerValidationPolicyWorkflows($post_id, $post): void {
		$this->defaultSavePostChecks($post_id);
		$this->removeAllBeforeActionExecution('save_post', function () use ($post) {
			ManageHooks::register($this, $post);
			ManageCredentials::register($this, $post);
		});
	}

	private function defaultSavePostChecks($post_id) {
		$onAutoSave = defined('DOING_AUTOSAVE') && DOING_AUTOSAVE;
		$onNoPermissions = ! current_user_can('edit_post', $post_id);

		if ($onAutoSave || $onNoPermissions) {
			return null;
		}
	}

	private function removeAllBeforeActionExecution($action, $callback) {
		// Backup all filters and remove all actions temporary
		global $wp_filter, $merged_filters;
		$backup_wp_filter = $wp_filter;
		$backup_merged_filters = $merged_filters;
		remove_all_actions($action);

		// Execute the callback for the action once
		$callback();

		// Restore filters
		$wp_filter = $backup_wp_filter;
		$merged_filters = $backup_merged_filters;
	}
}
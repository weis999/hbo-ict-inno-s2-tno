<?php

namespace TNO\EssifLab\Application\Controllers;

defined('ABSPATH') or die();

use TNO\EssifLab\Application\Workflows\ManageCredentials;
use TNO\EssifLab\Application\Workflows\ManageHooks;
use TNO\EssifLab\Application\Workflows\ManageInputs;
use TNO\EssifLab\Application\Workflows\ManageIssuers;
use TNO\EssifLab\Application\Workflows\ManageSchemas;
use TNO\EssifLab\Contracts\Abstracts\Controller;
use TNO\EssifLab\Contracts\Interfaces\RegistersPostTypes;
use TNO\EssifLab\Presentation\Views\ListWithAddAndUse;
use TNO\EssifLab\Presentation\Views\ListWithCustomAdd;
use TNO\EssifLab\Services\PostUtil;

class Admin extends Controller implements RegistersPostTypes {
    private const POST_TYPE = 'postType';
    private const RELATED = 'related';
    private const WORKFLOW = 'workflow';
    private const HEADINGS = 'headings';
    private const TITLE = 'title';
    private const CONTEXT = 'context';
    private $icon = 'dashicons-lock';

	private $capability = 'manage_options';

	private $types = [
		'validation-policy' => [
			self::POST_TYPE => true,
			self::RELATED => ['hook', 'credential'],
		],
		'hook' => [
			self::WORKFLOW => ManageHooks::class,
			'args' => [
				self::HEADINGS => [self::CONTEXT, 'target'],
			],
		],
		'credential' => [
			self::POST_TYPE => true,
			self::WORKFLOW => ManageCredentials::class,
			self::RELATED => ['input', 'issuer', 'schema'],
			'args' => [
				self::HEADINGS => [self::TITLE, 'inputs'],
			],
		],
		'input' => [
			self::WORKFLOW => ManageInputs::class,
			'args' => [self::HEADINGS => [self::CONTEXT, 'name']],
		],
		'issuer' => [
			self::POST_TYPE => true,
			self::WORKFLOW => ManageIssuers::class,
			'args' => [self::HEADINGS => [self::TITLE, 'signature']],
		],
		'schema' => [
			self::POST_TYPE => true,
			self::WORKFLOW => ManageSchemas::class,
			'args' => [self::HEADINGS => [self::TITLE, 'URL']],
		],
	];

    private $manageHooks;

    public function getActions(): array {
		$this->addAction('init', $this, 'registerPostTypes');
		$this->registerAdminMenuItem();
		$this->registerMetaBoxes();
		$this->registerWorkflowsHandler();

        $this->addAction('wp_ajax_essif_delete_hooks', $this, 'essif_ajax_delete_hooks_handler');

		return $this->actions;
	}

	private function typeHasPostType($type) {
		$attr = self::POST_TYPE;

		return array_key_exists($attr, $type) && is_bool($type[$attr]) && $type[$attr] === true;
	}

	private function typesWithPostType() {
		return array_filter($this->types, function ($type) {
			return $this->typeHasPostType($type);
		});
	}

	public function registerPostTypes(): void {
		foreach ($this->typesWithPostType() as $postType => $attrs) {
			$this->addPostType($postType);
		}
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

	private function addPostType($name): void {
		$singular = ucfirst(str_replace('-', ' ', $name));
		$plural = $this->getPluralFromSingular($singular);
		register_post_type($name, [
			'labels' => [
				'name' => $plural,
				'singular_name' => $singular,
			],
			'supports' => [self::TITLE],
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => $this->getDomain(),
		]);
	}

	private function registerAdminMenuItem(): void {
		add_action('admin_menu', function () {
			add_menu_page($this->getName(), $this->getName(), $this->capability, $this->getDomain(), null, $this->icon);
		});
	}

	private function typesWithRelations() {
		return array_filter($this->types, function ($type) {
			$attr = 'related';

			return array_key_exists($attr, $type) && is_array($type[$attr]) && count($type[$attr]);
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

	private function registerWorkflowsHandler(): void {
		foreach ($this->typesWithRelations() as $type => $attrs) {
			add_action('save_post_'.$type, function ($post_id, $post) use ($type) {
				$this->defaultSavePostChecks($post_id);
				$this->removeAllBeforeActionExecution('save_post_'.$type, function () use ($type, $post) {
					$this->addWorkflows($type, $post);
				});
			}, 10, 2);
		}
	}

	private function getRelatedTypes($type) {
		$relations = array_key_exists($type, $this->types) && array_key_exists(self::RELATED, $this->types[$type]) ? $this->types[$type][self::RELATED] : [];

		$output = [];

		foreach ($relations as $relation) {
			if (array_key_exists($relation, $this->types)) {
				$output[$relation] = $this->types[$relation];
			}
		}

		return $output;
	}

	private function getBaseName($subject) {
		return $this->getDomain().'_'.$subject;
	}

	private function getCallableWorkflowFunc($typeAttr, $funcName): string {
		$func = array_key_exists(self::WORKFLOW, $typeAttr) ? $typeAttr[self::WORKFLOW].'::'.$funcName : $funcName;

		return is_callable($func) ? $func : bool_from_yn('n');
	}

	private function addWorkflows($type, $post) {
		$relations = $this->getRelatedTypes($type);
		foreach ($relations as $k => $v) {
			$func = $this->getCallableWorkflowFunc($v, 'register');
			if ($func) {
				call_user_func($func, $this, $post, $this->getBaseName($k));
			}
		}
	}

	private function getMetaBoxArgs($v) {
		$func = $this->getCallableWorkflowFunc($v, 'options');
		$args = array_key_exists('args', $v) ? $v['args'] : [];
		$args['options'] = $func ? call_user_func($func) : [];

		return $args;
	}

	private function registerMetaBoxes(): void {
		add_action('add_meta_boxes', function () {
			foreach (array_keys($this->typesWithRelations()) as $type) {
				$relations = $this->getRelatedTypes($type);
				foreach ($relations as $k => $v) {
					$args = $this->getMetaBoxArgs($v);
					if ($this->typeHasPostType($v)) {
						$this->addListWithAddAndUseMetaBox($type, $k, $args);
					} else {
						$this->addListWithCustomAddMetaBox($type, $k, $args);
					}
				}
			}
		});
	}

	private function addListWithCustomAddMetaBox($postType, $subject, $args): void {
		$data = $this->getPluginData();
		$args = array_merge(PostUtil::getJsonPostContentAsArray(), [
			'subject' => $subject,
			'baseName' => $this->getBaseName($subject),
		], $args);
		$this->addMetaBox($postType, $subject, new ListWithCustomAdd($data, $args));
	}

	private function addListWithAddAndUseMetaBox($postType, $subject, $args): void {
		$data = $this->getPluginData();
		$args = array_merge(PostUtil::getJsonPostContentAsArray(), [
			'subject' => $subject,
			'baseName' => $this->getBaseName($subject),
		], $args);
		$this->addMetaBox($postType, $subject, new ListWithAddAndUse($data, $args));
	}

	private function addMetaBox($screen, $title, $component): void {
		$name = strtolower(str_replace(' ', '-', $title));
		$title = ucfirst($this->getPluralFromSingular($title));
		add_meta_box("$screen-$name", $title, [$component, 'display'], $screen, 'normal');
	}

	public function getFilters(): array {
		return $this->filters;
	}

	public function essif_ajax_delete_hooks_handler() {
        $this->manageHooks = new ManageHooks($this->getPluginData(), get_post(52));
        $this->manageHooks->delete($_POST);

        return "deleted";
    }
}
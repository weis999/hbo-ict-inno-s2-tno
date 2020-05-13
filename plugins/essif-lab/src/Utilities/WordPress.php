<?php

namespace TNO\EssifLab\Utilities;

use TNO\EssifLab\Utilities\Contracts\BaseUtility;

class WordPress extends BaseUtility {
	const ADD_ACTION = 'add_action';

	const ADD_FILTER = 'add_filter';

	const DO_ACTION = 'do_action';

	const APPLY_FILTERS = 'apply_filters';

	const ADD_NAV_ITEM = 'add_menu_page';

	const ADD_META_BOX = 'add_meta_box';

	protected $functions = [
		self::ADD_ACTION => [self::class, 'addAction'],
		self::ADD_FILTER => [self::class, 'addFilter'],
		self::DO_ACTION => [self::class, 'doAction'],
		self::APPLY_FILTERS => [self::class, 'applyFilter'],
		self::ADD_META_BOX => [self::class, 'addMetaBox'],
		self::ADD_NAV_ITEM =>  [self::class, 'addAdminNav'],
	];

	static function addAction(string $hook, callable $callback, int $priority = 10, int $accepted_args = 1): void {
		add_action($hook, $callback, $priority, $accepted_args);
	}

	static function addFilter(string $hook, callable $callback, int $priority = 10, int $accepted_args = 1): void {
		add_filter($hook, $callback, $priority, $accepted_args);
	}

	static function doAction(string $tag, ...$params): void {
		do_action($tag, ...$params);
	}

	static function applyFilter(string $tag, $value, ...$params) {
		return apply_filters($tag, $value, ...$params);
	}

	static function addMetaBox(string $id, string $title, callable $callback, string $screen):void {
		add_meta_box($id, $title, $callback, $screen, 'normal');
	}

	static function addAdminNav(string $title, string $capability, string $slug, string $icon): void {
		add_menu_page($title, $title, $capability, $slug, null, $icon);
	}

	static function createModelType(string $postType, array $args = []): void {
		register_post_type($postType, $args);
	}

	static function createModel(array $args): bool {
		$result = wp_insert_post($args, true);
		if (!is_int($result)) {
			throw $result;
		}

		return $result;
	}

	static function deleteModel(int $postId): bool {
		return wp_delete_post($postId, true);
	}

	static function getModels(array $args = []): array {
		return get_posts(array_merge([
			'numberposts' => -1,
			'post_type' => 'any',
		], $args));
	}

	static function createModelMeta(int $postId, string $key, $value): bool {
		return add_post_meta($postId, $key, $value, false);
	}

	static function deleteModelMeta(int $postId, string $key, $value): bool {
		return delete_post_meta($postId, $key, $value);
	}

	static function getModelMeta(int $postId, string $key): array {
		$meta = get_post_meta($postId, $key, false);

		return is_array($meta) ? $meta : [];
	}

	static function getEditModelLink(int $postId): string {
		return get_edit_post_link($postId);
	}

	static function getCreateModelLink(string $postType): string {
		return add_query_arg(['post_type' => $postType], admin_url('post-new.php'));
	}
}
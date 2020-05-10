<?php

namespace TNO\EssifLab\ModelManagers;

use WP_Post;
use TNO\EssifLab\Applications\Contracts\Application;
use TNO\EssifLab\Constants;
use TNO\EssifLab\ModelManagers\Contracts\ModelManager;
use TNO\EssifLab\ModelManagers\Exceptions\InvalidModelType;
use TNO\EssifLab\ModelManagers\Exceptions\MissingIdentifier;
use TNO\EssifLab\Models\Contracts\Model;

class WordPressPostTypes implements ModelManager {
	private $model;

	private $relationKey;

	public function __construct(Application $application) {
		$this->model = $application;
		$this->relationKey = $application->getNamespace().'_'.Constants::MANAGER_TYPE_RELATION_ID_NAME;
	}

	function insert(Model $model): bool {
		return wp_insert_post($model->getAttributes()) !== 0;
	}

	function update(Model $model): bool {
		if (self::getModelIdentifier($model) < 0) {
			throw new MissingIdentifier($model->getSingularName());
		}

		return $this->insert($model);
	}

	function select(Model $model, array $criteria = []): array {
		$posts = get_posts(array_merge(Constants::MANAGER_DEFAULT_SELECT_CRITERIA, [
			Constants::MANAGER_TYPE_ID_CRITERIA_NAME => $model->getTypeName(),
		], $criteria));

		return self::postsToModels($posts);
	}

	function delete(Model $model): bool {
		$id = $this->getModelIdentifier($model);
		if ($id < 0) {
			throw new MissingIdentifier($model->getSingularName());
		}
		$result = wp_delete_post($id);

		return $result !== null || $result !== false;
	}

	function insertRelation(Model $from, Model $to): bool {
		$fromId = $this->getModelIdentifier($from);
		$toId = $this->getModelIdentifier($to);

		if ($fromId < 0) {
			throw new MissingIdentifier($from->getSingularName());
		}

		if ($toId < 0) {
			throw new MissingIdentifier($to->getSingularName());
		}

		return boolval(add_post_meta($fromId, $this->relationKey, $toId));
	}

	function deleteRelation(Model $from, Model $to): bool {
		$fromId = $this->getModelIdentifier($from);
		$toId = $this->getModelIdentifier($to);

		if ($fromId < 0) {
			throw new MissingIdentifier($from->getSingularName());
		}

		if ($toId < 0) {
			throw new MissingIdentifier($to->getSingularName());
		}

		return delete_post_meta($fromId, $this->relationKey, $toId);
	}

	function deleteAllRelations(Model $model): bool {
		$id = $this->getModelIdentifier($model);

		if ($id < 0) {
			throw new MissingIdentifier($model->getSingularName());
		}

		return delete_post_meta($id, $this->relationKey);
	}

	function selectAllRelations(Model $model): array {
		$id = $this->getModelIdentifier($model);

		if ($id < 0) {
			throw new MissingIdentifier($model->getSingularName());
		}

		$relationIds = get_post_meta($id, $this->relationKey);

		$posts = get_posts(array_merge(Constants::TYPE_DEFAULT_TYPE_ARGS, [
			'post_type' => 'any',
			'post__in' => $relationIds,
		]));

		return self::postsToModels($posts);
	}

	static function getModelIdentifier(Model $model): int {
		$attributes = $model->getAttributes();
		$idKey = Constants::TYPE_INSTANCE_IDENTIFIER_ATTR;

		return array_key_exists($idKey, $attributes) && intval($attributes[$idKey]) !== 0 ? intval($attributes[$idKey]) : -1;
	}

	private static function postsToModels(array $posts): array {
		return array_map(function (WP_Post $post) {
			return self::modelFactory($post->to_array());
		}, $posts);
	}

	static function modelFactory(array $args): Model {
		$type = array_key_exists(Constants::MANAGER_TYPE_ID_CRITERIA_NAME, $args) ? $args[Constants::MANAGER_TYPE_ID_CRITERIA_NAME] : '';

		$className = implode('', array_map('ucfirst', explode(' ', str_replace('-', ' ', $type))));
		$FQCN = Constants::TYPE_NAMESPACE.'\\'.$className;

		if (empty($type) || ! class_exists($FQCN) || ! in_array(Model::class, class_implements($FQCN))) {
			throw new InvalidModelType($FQCN);
		}

		$attrs = self::extractAttributesFromArgs($args);

		return new $FQCN($attrs);
	}

	private static function extractAttributesFromArgs(array $args): array {
		$id = array_key_exists(Constants::TYPE_INSTANCE_IDENTIFIER_ATTR, $args) ? $args[Constants::TYPE_INSTANCE_IDENTIFIER_ATTR] : 0;
		$title = array_key_exists('post_title', $args) ? $args['post_title'] : '';
		$content = array_key_exists('post_content', $args) ? $args['post_content'] : '';
		$contentToJson = self::jsonStringToAssocArray($content);
		$description = empty($contentToJson) ? $content : '';

		return array_filter(array_merge($contentToJson, [
			Constants::TYPE_INSTANCE_IDENTIFIER_ATTR => $id,
			Constants::TYPE_INSTANCE_TITLE_ATTR => $title,
			Constants::TYPE_INSTANCE_DESCRIPTION_ATTR => $description,
		]));
	}

	private static function jsonStringToAssocArray(string $string): array {
		$json = json_decode($string);
		$isValidJson = json_last_error() === JSON_ERROR_NONE;
		$isAssocArray = is_array($json) && array_keys($json) !== range(0, count($json) - 1);
		if ($isValidJson && $isAssocArray) {
			return $json;
		}

		return [];
	}
}
<?php

namespace TNO\EssifLab\ModelManagers;

use TNO\EssifLab\Applications\Contracts\Application;
use TNO\EssifLab\Constants;
use TNO\EssifLab\ModelManagers\Contracts\BaseModelManager;
use TNO\EssifLab\ModelManagers\Exceptions\InvalidModelType;
use TNO\EssifLab\ModelManagers\Exceptions\MissingIdentifier;
use TNO\EssifLab\Models\Contracts\Model;
use TNO\EssifLab\Utilities\Contracts\BaseUtility;
use TNO\EssifLab\Utilities\Contracts\Utility;
use WP_Post;

class WordPressPostTypes extends BaseModelManager {
	private $relationKey;

	public function __construct(Application $application, Utility $utility) {
		parent::__construct($application, $utility);
		$this->relationKey = $application->getNamespace().'_'.Constants::MANAGER_TYPE_RELATION_ID_NAME;
	}

	function insert(Model $model): bool {
		return $this->utility->call(BaseUtility::CREATE_MODEL, $model->getAttributes());
	}

	function update(Model $model): bool {
		if (self::getModelIdentifier($model) < 0) {
			throw new MissingIdentifier($model->getSingularName());
		}

		return $this->insert($model);
	}

	function select(Model $model, array $criteria = []): array {
		$args = array_merge([
			Constants::MANAGER_TYPE_ID_CRITERIA_NAME => $model->getTypeName(),
		], $criteria);
		$posts = $this->utility->call(BaseUtility::GET_MODELS, $args);

		return self::postsToModels($posts);
	}

	function delete(Model $model): bool {
		$id = $this->getModelIdentifier($model);
		if ($id < 0) {
			throw new MissingIdentifier($model->getSingularName());
		}
		$result = $this->utility->call(BaseUtility::DELETE_MODEL, $id);

		$this->deleteAllRelations($model);

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

		$fromTo = boolval($this->utility->call(BaseUtility::CREATE_MODEL_META, $fromId, $this->relationKey, $toId));
		$toFrom = boolval($this->utility->call(BaseUtility::CREATE_MODEL_META, $toId, $this->relationKey, $fromId));

		return $fromTo && $toFrom;
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

		$fromTo = $this->utility->call(BaseUtility::DELETE_MODEL_META, $fromId, $this->relationKey, $toId);
		$toFrom = $this->utility->call(BaseUtility::DELETE_MODEL_META, $toId, $this->relationKey, $fromId);

		return $fromTo && $toFrom;
	}

	function deleteAllRelations(Model $model): bool {
		$id = $this->getModelIdentifier($model);

		if ($id < 0) {
			throw new MissingIdentifier($model->getSingularName());
		}

		$relationIds = get_post_meta($id, $this->relationKey);
		foreach ($relationIds as $relationId) {
			$this->utility->call(BaseUtility::DELETE_MODEL_META, $relationId, $this->relationKey, $id);
		}

		return $this->utility->call(BaseUtility::DELETE_MODEL_META, $id, $this->relationKey);
	}

	function selectAllRelations(Model $model): array {
		$id = $this->getModelIdentifier($model);

		if ($id < 0) {
			throw new MissingIdentifier($model->getSingularName());
		}

		$relationIds = $this->utility->call(BaseUtility::GET_MODEL_META, $id, $this->relationKey);

		$args = array_merge(Constants::TYPE_DEFAULT_TYPE_ARGS, [
			'post_type' => 'any',
			'post__in' => $relationIds,
		]);
		$posts = $this->utility->call(BaseUtility::GET_MODELS, $args);

		return self::postsToModels($posts);
	}

	private static function getModelIdentifier(Model $model): int {
		$attributes = $model->getAttributes();
		$idKey = Constants::TYPE_INSTANCE_IDENTIFIER_ATTR;

		return array_key_exists($idKey, $attributes) && intval($attributes[$idKey]) !== 0 ? intval($attributes[$idKey]) : -1;
	}

	private static function postsToModels(array $posts): array {
		return array_map(function (WP_Post $post) {
			return self::modelFactory($post->to_array());
		}, $posts);
	}

	private static function modelFactory(array $args): Model {
		$type = array_key_exists(Constants::MANAGER_TYPE_ID_CRITERIA_NAME, $args) ? $args[Constants::MANAGER_TYPE_ID_CRITERIA_NAME] : '';

		$className = implode('', array_map('ucfirst', explode(' ', str_replace('-', ' ', $type))));
		$FQN = Constants::TYPE_NAMESPACE.'\\'.$className;

		if (empty($type) || ! class_exists($FQN) || ! in_array(Model::class, class_implements($FQN))) {
			throw new InvalidModelType($FQN);
		}

		$attrs = self::extractAttributesFromArgs($args);

		return new $FQN($attrs);
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
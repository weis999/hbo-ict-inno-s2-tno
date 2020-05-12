<?php

namespace TNO\EssifLab\Tests\Stubs;

use TNO\EssifLab\ModelManagers\Contracts\BaseModelManager;
use TNO\EssifLab\Models\Contracts\Model;

class ModelManager extends BaseModelManager {
	function insert(Model $model): bool {
		return true;
	}

	function delete(Model $model): bool {
		return true;
	}

	function update(Model $model): bool {
		return true;
	}

	function select(Model $model, array $criteria = []): array {
		return [];
	}

	function insertRelation(Model $from, Model $to): bool {
		return true;
	}

	function deleteRelation(Model $from, Model $to): bool {
		return true;
	}

	function deleteAllRelations(Model $model): bool {
		return true;
	}

	function selectAllRelations(Model $model): array {
		return [];
	}
}
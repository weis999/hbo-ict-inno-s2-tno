<?php

namespace TNO\EssifLab\ModelManagers\Contracts;

use TNO\EssifLab\Applications\Contracts\Application;
use TNO\EssifLab\Models\Contracts\Model;

interface ModelManager {
	public function __construct(Application $application);

	function insert(Model $model): bool;

	function delete(Model $model): bool;

	function update(Model $model): bool;

	function select(Model $model, array $criteria = []): array;

	function insertRelation(Model $from, Model $to): bool;

	function deleteRelation(Model $from, Model $to): bool;

	function deleteAllRelations(Model $model): bool;

	function selectAllRelations(Model $model): array;
}
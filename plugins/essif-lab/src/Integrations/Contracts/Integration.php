<?php

namespace TNO\EssifLab\Integrations\Contracts;

use TNO\EssifLab\Applications\Contracts\Application;
use TNO\EssifLab\ModelManagers\Contracts\ModelManager;
use TNO\EssifLab\Models\Contracts\Model;
use TNO\EssifLab\Utilities\Contracts\Utility;

interface Integration {
	function __construct(Application $application, ModelManager $manager, Utility $utility);

	function install(): void;

	function registerModelType(Model $model): void;

	function registerModelRelations(Model $model): void;

	function getApplication(): Application;

	function getModelManager(): ModelManager;

	function getUtility(): Utility;
}
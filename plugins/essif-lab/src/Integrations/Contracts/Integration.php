<?php

namespace TNO\EssifLab\Integrations\Contracts;

use TNO\EssifLab\Applications\Contracts\Application;
use TNO\EssifLab\ModelManagers\Contracts\ModelManager;
use TNO\EssifLab\Models\Contracts\Model;

interface Integration {
	function __construct(Application $application, ModelManager $manager, array $utilities = []);

	function install(): void;

	function registerModelType(Model $model): void;

	function registerModelRelations(Model $model): void;

	function useUtility(string $name, ...$parameters);

	function getApplication(): Application;
}
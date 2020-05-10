<?php

namespace TNO\EssifLab\Tests\Stubs;

use TNO\EssifLab\Applications\Contracts\Application;
use TNO\EssifLab\Integrations\Contracts\BaseIntegration;
use TNO\EssifLab\ModelManagers\Contracts\ModelManager;
use TNO\EssifLab\Models\Contracts\Model;

class Integration extends BaseIntegration {
	public function __construct(Application $application, ModelManager $manager, array $utilities = []) {
		$this->utilities = [
			self::GET_EDIT_TYPE_LINK => function ($x) {
				return $x;
			}
		];

		parent::__construct($application, $manager, $utilities);
	}

	function install(): void {
		// TODO: Implement install() method.
	}

	function registerModelType(Model $model): void {
		// TODO: Implement registerModelType() method.
	}

	function registerModelRelations(Model $model): void {
		// TODO: Implement registerModelRelations() method.
	}
}
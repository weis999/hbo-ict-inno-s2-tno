<?php

namespace TNO\EssifLab\Integrations\Contracts;

use TNO\EssifLab\Applications\Contracts\Application;
use TNO\EssifLab\ModelManagers\Contracts\ModelManager;
use TNO\EssifLab\ModelRenderers\Contracts\ModelRenderer;
use TNO\EssifLab\Utilities\Contracts\Utility;

interface Integration {
	function __construct(Application $application, ModelManager $manager,ModelRenderer $renderer, Utility $utility);

	function install(): void;

	function getApplication(): Application;

	function getModelManager(): ModelManager;

	function getUtility(): Utility;
}
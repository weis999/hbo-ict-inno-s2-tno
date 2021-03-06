<?php

namespace TNO\EssifLab\ModelRenderers;

use TNO\EssifLab\Integrations\Contracts\Integration;
use TNO\EssifLab\Models\Contracts\Model;
use TNO\EssifLab\Views\TypeList;

class WordPressMetaBox implements Contracts\ModelRenderer {
	function renderListAndFormView(Integration $integration, Model $model, array $attrs = []): string {
		$view = new TypeList($integration, $model, $attrs);
		return $view->render();
	}
}
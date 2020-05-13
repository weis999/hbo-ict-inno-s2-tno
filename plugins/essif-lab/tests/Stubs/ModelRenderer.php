<?php

namespace TNO\EssifLab\Tests\Stubs;

use TNO\EssifLab\Integrations\Contracts\Integration;
use TNO\EssifLab\Models\Contracts\Model;

class ModelRenderer implements \TNO\EssifLab\ModelRenderers\Contracts\ModelRenderer {
	private $renderListAndFormViewCalled = false;

 	private $attrsParamWhereRenderListAndFormViewWasCalledWith;

	function renderListAndFormView(Integration $integration, Model $model, array $attrs = []): string {
		$this->renderListAndFormViewCalled = true;

		$this->attrsParamWhereRenderListAndFormViewWasCalledWith = $attrs;

		return '';
	}

	/**
	 * @return bool
	 */
	public function isRenderListAndFormViewCalled(): bool {
		return $this->renderListAndFormViewCalled;
	}

	/**
	 * @return mixed
	 */
	public function getAttrsParamWhereRenderListAndFormViewWasCalledWith() {
		return $this->attrsParamWhereRenderListAndFormViewWasCalledWith;
	}
}
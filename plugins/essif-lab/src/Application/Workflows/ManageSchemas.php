<?php

namespace TNO\EssifLab\Application\Workflows;

use TNO\EssifLab\Contracts\Abstracts\Workflow;

class ManageSchemas extends Workflow {
	public static function options(): array {
		// TODO: load all selectable options to be displayed in the select lists.
		return [];
	}

	public function add($attrs) {
		// TODO: add a schema to a credential
	}

	public function edit($request) {
		// TODO: edit a schema to a credential
	}

	public function delete($request) {
		// TODO: delete a schema to a credential
	}
}
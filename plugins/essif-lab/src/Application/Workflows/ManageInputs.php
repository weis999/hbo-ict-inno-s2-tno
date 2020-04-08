<?php

namespace TNO\EssifLab\Application\Workflows;

use TNO\EssifLab\Contracts\Abstracts\Workflow;

class ManageInputs extends Workflow {
	public static function options(): array {
		// TODO: load all selectable options to be displayed in the select lists.
		return [];
	}

	public function add($attrs) {
		// TODO: add a input to a credential
	}

	public function edit($request) {
		// TODO: edit a input to a credential
	}

	public function delete($request) {
		// TODO: delete a input to a credential
	}
}
<?php

namespace TNO\EssifLab\Application\Workflows;

use TNO\EssifLab\Contracts\Abstracts\Workflow;

class ManageCredentials extends Workflow {
	public static function options(): array {
		// TODO: load all selectable options to be displayed in the select lists.
		return [];
	}

	public function add($request) {
		// TODO: add a credential to a validation policy
	}

	public function edit($request) {
		// TODO: edit a credential of a validation policy
	}

	public function delete($request) {
		// TODO: delete a credential of a validation policy
	}
}
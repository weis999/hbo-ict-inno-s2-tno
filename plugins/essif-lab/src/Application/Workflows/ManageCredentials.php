<?php

namespace TNO\EssifLab\Application\Workflows;

use TNO\EssifLab\Contracts\Abstracts\Workflow;

class ManageCredentials extends Workflow {
	public static function getActionName(): string {
		return 'credentials';
	}

	public function add($attrs) {
		// TODO: add a hook to a validation policy
		var_dump('adding a credential', $attrs);
		die();
	}

	public function edit($request) {
		// TODO: edit a hook of a validation policy
	}

	public function delete($request) {
		// TODO: delete a hook of a validation policy
	}
}
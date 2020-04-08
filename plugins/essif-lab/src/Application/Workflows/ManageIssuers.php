<?php

namespace TNO\EssifLab\Application\Workflows;

use TNO\EssifLab\Contracts\Abstracts\Workflow;

class ManageIssuers extends Workflow {
	public static function options(): array {
		// TODO: load all selectable options to be displayed in the select lists.
		return [];
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
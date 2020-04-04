<?php

namespace TNO\EssifLab\Application\Workflows;

use TNO\EssifLab\Contracts\Abstracts\Core;

class ManageHooks extends Core {
	protected $post_id;

	protected $post;

	public function __construct($pluginData, $requestData) {
		parent::__construct($pluginData);
		[$post_id, $post] = $requestData;
		$this->post_id = $post_id;
		$this->post = $post;
	}

	public function execute($request): void {
		[$action, $payload] = $request;
		if (method_exists($this, $action)) {
			$this->{$action}($payload);
		}
	}

	public function add($attrs) {
		// TODO: add a hook to a validation policy
	}

	public function edit($attrs) {
		// TODO: edit a hook of a validation policy
	}

	public function delete($id) {
		// TODO: delete a hook of a validation policy
	}
}
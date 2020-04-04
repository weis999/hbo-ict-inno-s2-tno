<?php

namespace TNO\EssifLab\Contracts\Abstracts;

use TNO\EssifLab\Contracts\Interfaces\Workflow as IWorkflow;

abstract class Workflow extends Core implements IWorkflow {
	protected $actionKey = 'action';

	protected $post_id;

	protected $post;

	public function __construct($pluginData, $requestData) {
		parent::__construct($pluginData);
		[$post_id, $post] = $requestData;
		$this->post_id = $post_id;
		$this->post = $post;
	}

	public function execute(array $request): void {
		$action = $this->hasActionKey($request) ? $request[$this->actionKey] : null;
		$payload = $this->removeActionKey($request);
		if (method_exists($this, $action)) {
			$this->{$action}($payload);
		}
	}

	private function removeActionKey($array) {
		$backup = $array;
		if (array_key_exists($this->actionKey, $backup)) {
			unset($backup[$this->actionKey]);
		}
		return $backup;
	}

	private function hasActionKey($array) {
		return is_array($array) && array_key_exists($this->actionKey, $array);
	}
}
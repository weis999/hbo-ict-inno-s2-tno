<?php

namespace TNO\EssifLab\Contracts\Abstracts;

use TNO\EssifLab\Contracts\Interfaces\Workflow as IWorkflow;
use TNO\EssifLab\Contracts\Interfaces\Core as ICore;

abstract class Workflow extends Core implements IWorkflow {
	protected $actionKey = 'action';

	protected $post;

	public function __construct($pluginData, $post) {
		parent::__construct($pluginData);
		$this->post = $post;
	}

	public static function register(ICore $pluginData, $post): void {
		$key = static::getFullActionName($pluginData->getDomain(), static::getActionName());
		if (is_array($_POST) && array_key_exists($key, $_POST)) {
			$request = $_POST[$key];
			$workflow = new static($pluginData->getPluginData(), $post);
			$workflow->execute($request);
		}
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

	public static function getFullActionName($prefix, $subject) {
		return $prefix.'_'.$subject;
	}
}
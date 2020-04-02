<?php

namespace TNO\EssifLab\Application;

defined('ABSPATH') or die();

use TNO\EssifLab\Abstracts\Core;
use TNO\EssifLab\Controllers\Activate;
use TNO\EssifLab\Controllers\Admin;
use TNO\EssifLab\Controllers\Deactivate;
use TNO\EssifLab\Controllers\NotAdmin;

class Main extends Core {
	public function __construct(array $plugin_data = []) {
		parent::__construct($plugin_data);
		$this->initializeActivationAndDeactivationHook();
		$this->initializeAdminComponent();
		$this->initializeNotAdminComponent();
	}

	private function initializeActivationAndDeactivationHook() {
		$file = $this->get_path().'index.php';

		register_activation_hook($file, [$this, 'activate']);
		register_deactivation_hook($file, [$this, 'deactivate']);
	}

	public function activate() {
		new Activate($this->get_plugin_data());
	}

	public function deactivate() {
		new Deactivate($this->get_plugin_data());
	}

	private function initializeAdminComponent() {
		$component = new Admin($this->get_plugin_data());
		if (is_admin()) {
			$this->addActionsAndFilters($component->getActions(), $component->getFilters());
		}
	}

	private function initializeNotAdminComponent() {
		$component = new NotAdmin($this->get_plugin_data());
		if (! is_admin()) {
			$this->addActionsAndFilters($component->getActions(), $component->getFilters());
		}
	}

	private function addActionsAndFilters($actions, $filters) {
		$this->addHooks($actions, 'add_action');
		$this->addHooks($filters, 'add_filter');
	}

	private function addHooks($hooks, $cb) {
		foreach ($hooks as $hook) {
			call_user_func($cb, $hook['hook'], [
				$hook['component'],
				$hook['callback'],
			], $hook['priority'], $hook['accepted_args']);
		}
	}
}
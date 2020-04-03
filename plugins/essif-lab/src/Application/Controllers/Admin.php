<?php

namespace TNO\EssifLab\Application\Controllers;

defined('ABSPATH') or die();

use TNO\EssifLab\Contracts\Abstracts\Controller;
use TNO\EssifLab\Contracts\Interfaces\RegistersPostTypes;
use TNO\EssifLab\Presentation\Views\Dashboard;

class Admin extends Controller implements RegistersPostTypes {
	public function getActions(): array {
		$this->addAction('init', $this, 'registerPostTypes');
		$this->addAction('admin_menu', $this, 'registerAdminMenuItem');

		return $this->actions;
	}

	public function getFilters(): array {
		return $this->filters;
	}

	public function registerAdminMenuItem(): void {
		$component = [new Dashboard(), 'render'];
		add_menu_page($this->getName(), $this->getName(), 'manage_options', $this->getDomain(), $component, 'none');
	}

	public function registerPostTypes(): void {
		register_post_type($this->getDomain().'_validation_policy', [
			'labels' => [
				'name' => 'Validation policies',
				'singular_name' => 'Validation policy',
			],
			'public' => false,
		]);
	}
}
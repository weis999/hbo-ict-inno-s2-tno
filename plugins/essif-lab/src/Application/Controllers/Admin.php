<?php

namespace TNO\EssifLab\Controllers;

defined('ABSPATH') or die();

use TNO\EssifLab\Abstracts\Controller;
use TNO\EssifLab\Interfaces\RegistersPostTypes;

class Admin extends Controller implements RegistersPostTypes {
	public function getActions(): array {
		$this->addAction('init', $this, 'registerPostTypes');

		return $this->actions;
	}

	public function getFilters(): array {
		return $this->filters;
	}

	public function registerPostTypes(): void {
		register_post_type($this->getDomain().'_validation_policy', [
			'label' => '',
		]);
	}
}
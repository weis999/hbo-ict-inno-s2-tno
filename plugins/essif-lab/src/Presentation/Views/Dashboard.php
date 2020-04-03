<?php

namespace TNO\EssifLab\Presentation\Views;

use TNO\EssifLab\Contracts\Interfaces\View;

class Dashboard implements View {
	public function render(): string {
		return 'dashboard';
	}
}
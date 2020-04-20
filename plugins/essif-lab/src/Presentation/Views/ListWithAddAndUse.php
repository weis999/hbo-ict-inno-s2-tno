<?php

namespace TNO\EssifLab\Presentation\Views;

use TNO\EssifLab\Contracts\Abstracts\ListView;
use TNO\EssifLab\Contracts\Traits\WithAddPostLink;
use TNO\EssifLab\Contracts\Traits\WithUseAction;

class ListWithAddAndUse extends ListView {
	use WithAddPostLink, WithUseAction;

	public function __construct($pluginData, $args) {
		parent::__construct($pluginData, $args);

		$this->options = $this->getArg('options', $this->options);

		$usable = array_key_exists($this->subject, $this->options) ? $this->options[$this->subject] : [];
		$this->actions = [
			$this->getUseAction($this->subject, $usable),
			$this->getAddPostLink($this->subject)
		];
	}
}
<?php

namespace TNO\EssifLab\Contracts\Interfaces;

interface Component extends View {
	public function __construct(Core $plugin);
}
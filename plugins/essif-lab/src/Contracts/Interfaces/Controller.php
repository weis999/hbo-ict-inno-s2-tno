<?php

namespace TNO\EssifLab\Contracts\Interfaces;

interface Controller {
	public function getActions(): array;
	public function getFilters(): array;
}
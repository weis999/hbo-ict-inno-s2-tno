<?php

namespace TNO\EssifLab\Interfaces;

interface Controller {
	public function getActions(): array;
	public function getFilters(): array;
}
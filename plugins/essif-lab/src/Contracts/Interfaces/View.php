<?php

namespace TNO\EssifLab\Contracts\Interfaces;

interface View {
	public function render(): string;
	public function display(): void;
}
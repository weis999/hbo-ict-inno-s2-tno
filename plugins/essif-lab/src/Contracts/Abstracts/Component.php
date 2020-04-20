<?php

namespace TNO\EssifLab\Contracts\Abstracts;

use TNO\EssifLab\Contracts\Interfaces\Component as IComponent;
use TNO\EssifLab\Contracts\Interfaces\Core;

abstract class Component implements IComponent {
	/**
	 * @var Core
	 */
	protected $plugin;

	public function __construct(Core $plugin) {
		$this->plugin = $plugin;
	}

	public function display(): void {
		print $this->render();
	}

	/**
	 * @return Core
	 */
	public function getPlugin(): Core {
		return $this->plugin;
	}

	public static function generateElementAttrs($attrs) {
		$output = '';
		$attrs = is_array($attrs) ? array_filter($attrs) : [];
		if (count($attrs)) {
			$strings = [];
			foreach ($attrs as $key => $value) {
				$strings[] = $key.'="'.$value.'"';
			}
			$output .= ' '.join(' ', $strings);
		}

		return $output;
	}

	public static function generateClasses($classes) {
		$classes = is_array($classes) ? array_filter($classes) : [];

		return count($classes) ? join(' ', array_filter($classes)) : '';
	}
}
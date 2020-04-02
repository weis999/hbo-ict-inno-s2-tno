<?php

namespace TNO\EssifLab\Abstracts;

use \TNO\EssifLab\Interfaces\Controller as IController;
use TNO\EssifLab\Traits\Hooks;

abstract class Controller extends Core implements IController {
	use Hooks;
}
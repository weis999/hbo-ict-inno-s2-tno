<?php

namespace TNO\EssifLab\Contracts\Abstracts;

use TNO\EssifLab\Contracts\Interfaces\Controller as IController;
use TNO\EssifLab\Contracts\Traits\Hooks;

abstract class Controller extends Core implements IController {
	use Hooks;
}
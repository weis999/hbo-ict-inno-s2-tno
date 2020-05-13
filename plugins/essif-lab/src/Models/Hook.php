<?php

namespace TNO\EssifLab\Models;

use TNO\EssifLab\Models\Contracts\BaseModel;

class Hook extends BaseModel {
	protected $singular = 'hook';

	protected $relations = [
		Target::class
	];


}
<?php

namespace TNO\EssifLab\Tests\Stubs;

use TNO\EssifLab\Models\Contracts\BaseModel;

class Model extends BaseModel {
	protected $singular = 'model';

	protected $relations = [
		Model::class
	];
}
<?php

namespace TNO\EssifLab\Models;

use TNO\EssifLab\Models\Contracts\BaseModel;

class Credential extends BaseModel {
	protected $singular = 'credential';

	protected $relations = [
		Input::class,
		Issuer::class,
		Schema::class
	];
}
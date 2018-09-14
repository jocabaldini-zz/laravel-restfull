<?php

namespace App\Services;

use App\Contracts\ServicesContract;
use App\ModelTest;

class ModelTestService extends Service implements ServicesContract
{
	public function __construct(ModelTest $model)
	{
		$this->model = $model;
	}
}
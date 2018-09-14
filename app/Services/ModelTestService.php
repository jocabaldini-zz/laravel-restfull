<?php

namespace App\Services;

use App\Contracts\CrudServicesContract;
use App\ModelTest;
use App\Services\CrudService;

class ModelTestService extends CrudService implements CrudServicesContract
{
	public function __construct(ModelTest $model)
	{
		$this->model = $model;
	}
}
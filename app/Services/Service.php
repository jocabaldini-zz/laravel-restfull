<?php

namespace App\Services;

use App\Constants\HttpStatusCodeConstants;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Service
{
	protected $model;
	
	public function index(array $params) : array
	{
		$records = $this->model->all();

		if (! $records instanceof Collection) {
			throw new AppException(__('exception.defaults.index'), HttpStatusCodeConstants::INTERNAL_SERVER_ERROR);
		}

		return [
			'content' => $records
		];
	}

	public function create(array $params) : array
	{
		$record = $this->model->create($params);

		if (! $record instanceof Model) {
			throw new AppException(__('exception.defaults.create'), HttpStatusCodeConstants::INTERNAL_SERVER_ERROR);
		}

		return [
			'status' => HttpStatusCodeConstants::CREATED,
			'content' => $record
		];
	}

	public function read(array $params) : array
	{
		$record = $this->model->findOrFail($params['id']);

		return ['content' => $record];
	}

	public function update(array $params) : array
	{
		$record = $this->model->findOrFail($params['id']);
		unset($params['id']);
		
		if (! $record->update($params)) {
			throw new AppException(__('exception.defaults.update'), HttpStatusCodeConstants::INTERNAL_SERVER_ERROR);
		}

		return ['content' => $record];
	}

	public function delete(array $params) : array
	{
		$record = $this->model->findOrFail($params['id']);
		
		if (! $record->delete($params)) {
			throw new AppException(__('exception.defaults.delete'), HttpStatusCodeConstants::INTERNAL_SERVER_ERROR);
		}

		return ['content' => true];
	}
}
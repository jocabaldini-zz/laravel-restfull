<?php

namespace App\Services;

abstract class Service extends Controller
{
	protected $model;
	
	public function index(array $params) : array
	{
		$records = $this->model->all();

		if (! $records instanceof Collection) {
			throw new AppException(__('exception.defaults.index'), HttpStatusCode::INTERNAL_SERVER_ERROR);
		}

		return [
			'content' => $records
		];
	}

	public function create(array $params) : array
	{
		$record = $this->model->create($params);

		if (! $record instanceof Model) {
			throw new AppException(__('exception.defaults.create'), HttpStatusCode::INTERNAL_SERVER_ERROR);
		}

		return [
			'status' => HttpStatusCode::CREATED,
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
			throw new AppException(__('exception.defaults.update'), HttpStatusCode::INTERNAL_SERVER_ERROR);
		}

		return ['content' => $record];
	}

	public function delete(array $params) : array
	{
		$record = $this->model->findOrFail($params['id']);
		
		if (! $record->delete($params)) {
			throw new AppException(__('exception.defaults.delete'), HttpStatusCode::INTERNAL_SERVER_ERROR);
		}

		return ['content' => true];
	}
}
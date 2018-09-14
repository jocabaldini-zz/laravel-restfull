<?php

namespace App\Services;

abstract class Service extends Controller
{
	protected $model;
	
	protected function index(array $params)
	{
		$records = $this->model->all();

		if (! $records instanceof Collection) {
			throw new AppException(__('exception.defaults.index'), HttpStatusCode::INTERNAL_SERVER_ERROR);
		}

		return [
			'content' => $records
		];
	}

	protected function create(array $params)
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

	protected function read(array $params)
	{
		$record = $this->model->findOrFail($params['id']);

		return ['content' => $record];
	}

	protected function update(array $params)
	{
		$record = $this->model->findOrFail($params['id']);
		unset($params['id']);
		
		if (! $record->update($params)) {
			throw new AppException(__('exception.defaults.update'), HttpStatusCode::INTERNAL_SERVER_ERROR);
		}

		return ['content' => $record];
	}

	protected function delete(array $params)
	{
		$record = $this->model->findOrFail($params['id']);
		
		if (! $record->delete($params)) {
			throw new AppException(__('exception.defaults.delete'), HttpStatusCode::INTERNAL_SERVER_ERROR);
		}

		return ['content' => true];
	}
}
<?php

namespace App\Contracts;

interface CrudServicesContract
{
	public function index(array $params) : array;
	public function create(array $params) : array;
	public function read(array $params) : array;
	public function update(array $params) : array;
	public function delete(array $params) : array;
}
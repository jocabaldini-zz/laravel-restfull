<?php

namespace App\Http\Controllers;

use App\Helpers\MakeRequestHelper;
use App\Services\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class RestfullController extends Controller
{
	protected $service;

	protected function getService() : Service
	{
		return $this->service;
	}

	protected function setService(Service $service) : void
	{
		$this->service = $service;
	}

	protected function sendRequest(string $method, array $params) : JsonResponse
	{
		return MakeRequestHelper::sendRequest($this->getService(), $method, $params);
	}

	/**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : JsonResponse
    {
        return $this->sendRequest('index', $request->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : JsonResponse
    {
        return $this->sendRequest('create', $request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function show($id) : JsonResponse
    {
        return $this->sendRequest('read', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->sendRequest('update', array_merge($request->all(), ['id' => $id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) : JsonResponse
    {
        return $this->sendRequest('delete', ['id' => $id]);
    }    
}
<?php

namespace App\Http\Controllers;

use App\Contracts\CrudServicesContract;
use App\Http\Controllers\Controller;
use App\ModelTest;
use Illuminate\Http\Request;

class ModelTestController extends RestfullController
{
    public function __construct(CrudServicesContract $impl)
    {
        $this->service = $impl;
    }
}
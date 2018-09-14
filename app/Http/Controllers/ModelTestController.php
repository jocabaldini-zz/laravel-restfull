<?php

namespace App\Http\Controllers;

use App\Contracts\ServicesContract;
use App\Http\Controllers\Controller;
use App\ModelTest;
use Illuminate\Http\Request;

class ModelTestController extends RestfullController
{
    public function __construct(ServicesContract $impl)
    {
        $this->service = $impl;
    }
}

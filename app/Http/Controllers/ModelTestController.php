<?php

namespace App\Http\Controllers;

use App\ModelTest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModelTestController extends RestfullController
{
    public function __construct(ModelTestInterface $impl)
    {
        $this->service = $impl;
    }
}

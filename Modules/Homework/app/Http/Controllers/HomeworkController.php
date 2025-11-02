<?php

namespace Modules\Homework\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Homework\Services\HomeworkApiServiceInterface;

class HomeworkController extends Controller
{
    protected HomeworkApiServiceInterface $service;

    public function __construct(HomeworkApiServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->list($request->all());
    }

    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }
}

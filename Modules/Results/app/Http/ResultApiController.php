<?php

namespace Modules\Result\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;;
use Modules\Results\Services\ResultApiServiceInterface;

class ResultApiController extends Controller
{
    protected ResultApiServiceInterface $service;

    public function __construct(ResultApiServiceInterface $service)
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

<?php

namespace Modules\Results\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

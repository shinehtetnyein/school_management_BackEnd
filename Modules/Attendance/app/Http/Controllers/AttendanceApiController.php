<?php

namespace Modules\Attendance\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attendance\Services\AttendanceApiServiceInterface;

class AttendanceApiController extends Controller
{
    protected AttendanceApiServiceInterface $service;

    public function __construct(AttendanceApiServiceInterface $service)
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

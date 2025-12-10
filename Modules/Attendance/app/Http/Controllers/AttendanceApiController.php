<?php

namespace Modules\Attendance\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attendance\Services\AttendanceApiServiceInterface;
use Modules\Attendance\app\Http\Request\AttendanceRequest;
use Modules\Attendance\app\Http\Resource\AttendanceResource;

class AttendanceApiController extends Controller
{
    protected AttendanceApiServiceInterface $service;

    public function __construct(AttendanceApiServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $collection = $this->service->list($request->all());
        return AttendanceResource::collection($collection);
    }

    public function store(AttendanceRequest $request)
    {
        $att = $this->service->create($request->validated());
        return new AttendanceResource($att);
    }

    public function show($id)
    {
        return new AttendanceResource($this->service->show((int) $id));
    }

    public function update(AttendanceRequest $request, $id)
    {
        $att = $this->service->update((int) $id, $request->validated());
        return new AttendanceResource($att);
    }

    public function destroy($id)
    {
        $this->service->delete((int) $id);
        return response()->json(null, 204);
    }
}

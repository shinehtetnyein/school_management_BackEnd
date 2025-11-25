<?php

namespace Modules\Attendance\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attendance\Services\AttendanceApiServiceInterface;
use Modules\Attendance\app\Http\Requests\StoreAttendanceRequest;
use Modules\Attendance\app\Http\Requests\UpdateAttendanceRequest;
use Modules\Attendance\app\Http\Resources\AttendanceResource;

class AttendanceApiController extends Controller
{
    protected AttendanceApiServiceInterface $service;

    public function __construct(AttendanceApiServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $attendances = $this->service->list($request->all());
        return AttendanceResource::collection($attendances);
    }

    public function store(StoreAttendanceRequest $request)
    {
        $attendance = $this->service->create($request->validated());
        return new AttendanceResource($attendance);
    }

    public function show($id)
    {
        $attendance = $this->service->getAttendanceById($id);
        return new AttendanceResource($attendance);
    }

    public function update(UpdateAttendanceRequest $request, $id)
    {
        $attendance = $this->service->updateAttendance($id, $request->validated());
        return new AttendanceResource($attendance);
    }

    public function destroy($id)
    {
        $this->service->deleteAttendance($id);
        return response()->json(null, 204);
    }
}

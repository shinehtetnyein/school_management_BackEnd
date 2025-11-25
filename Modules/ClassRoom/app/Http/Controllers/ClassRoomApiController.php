<?php

namespace Modules\ClassRoom\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ClassRoom\Services\ClassRoomApiServiceInterface;
use Modules\ClassRoom\app\Http\Requests\StoreClassRoomRequest;
use Modules\ClassRoom\app\Http\Requests\UpdateClassRoomRequest;
use Modules\ClassRoom\app\Http\Resources\ClassRoomResource;

class ClassRoomApiController extends Controller
{
    protected ClassRoomApiServiceInterface $classRoomService;

    public function __construct(ClassRoomApiServiceInterface $classRoomService)
    {
        $this->classRoomService = $classRoomService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classRooms = $this->classRoomService->getAllClassRooms();
        return ClassRoomResource::collection($classRooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassRoomRequest $request)
    {
        $classRoom = $this->classRoomService->createClassRoom($request->validated());
        return new ClassRoomResource($classRoom);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $classRoom = $this->classRoomService->getClassRoomById($id);
        return new ClassRoomResource($classRoom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassRoomRequest $request, string $id)
    {
        $classRoom = $this->classRoomService->updateClassRoom($id, $request->validated());
        return new ClassRoomResource($classRoom);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->classRoomService->deleteClassRoom($id);
        return response()->json(null, 204);
    }
}

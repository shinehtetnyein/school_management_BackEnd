<?php

namespace Modules\Homework\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Homework\Services\HomeworkApiServiceInterface;
use Modules\Homework\app\Http\Requests\StoreHomeworkRequest;
use Modules\Homework\app\Http\Requests\UpdateHomeworkRequest;
use Modules\Homework\app\Http\Resources\HomeworkResource;

class HomeworkController extends Controller
{
    protected HomeworkApiServiceInterface $service;

    public function __construct(HomeworkApiServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $homeworks = $this->service->list($request->all());
        return HomeworkResource::collection($homeworks);
    }

    public function store(StoreHomeworkRequest $request)
    {
        $homework = $this->service->create($request->validated());
        return new HomeworkResource($homework);
    }

    public function show($id)
    {
        $homework = $this->service->getHomeworkById($id);
        return new HomeworkResource($homework);
    }

    public function update(UpdateHomeworkRequest $request, $id)
    {
        $homework = $this->service->updateHomework($id, $request->validated());
        return new HomeworkResource($homework);
    }

    public function destroy($id)
    {
        $this->service->deleteHomework($id);
        return response()->json(null, 204);
    }
}

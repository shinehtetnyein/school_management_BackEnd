<?php

namespace Modules\Homework\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Homework\Services\HomeworkApiServiceInterface;
use Modules\Homework\app\Http\Request\HomeworkRequest;
use Modules\Homework\app\Http\Resource\HomeworkResource;

class HomeworkController extends Controller
{
    protected HomeworkApiServiceInterface $service;

    public function __construct(HomeworkApiServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $collection = $this->service->list($request->all());
        return HomeworkResource::collection($collection);
    }

    public function store(HomeworkRequest $request)
    {
        $hw = $this->service->create($request->validated());
        return new HomeworkResource($hw);
    }

    public function show($id)
    {
        return new HomeworkResource($this->service->show((int) $id));
    }

    public function update(HomeworkRequest $request, $id)
    {
        $hw = $this->service->update((int)$id, $request->validated());
        return new HomeworkResource($hw);
    }

    public function destroy($id)
    {
        $this->service->delete((int) $id);
        return response()->json(null, 204);
    }
}

<?php

namespace Modules\Results\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Results\app\Http\Request\ResultRequest;
use Modules\Results\app\Http\Resource\ResultResource;
use Modules\Results\Services\ResultApiServiceInterface;

class ResultController extends Controller
{
    protected $service;

    public function __construct(ResultApiServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $results = $this->service->getAllResults();
        return ResultResource::collection($results);
    }

    public function store(ResultRequest $request)
    {
        $result = $this->service->createResult($request->validated());
        return new ResultResource($result);
    }

    public function show($id)
    {
        $result = $this->service->getResultById($id);
        return new ResultResource($result);
    }

    public function update(ResultRequest $request, $id)
    {
        $result = $this->service->updateResult($id, $request->validated());
        return new ResultResource($result);
    }

    public function destroy($id)
    {
        $this->service->deleteResult($id);
        return response()->json(null, 204);
    }
}

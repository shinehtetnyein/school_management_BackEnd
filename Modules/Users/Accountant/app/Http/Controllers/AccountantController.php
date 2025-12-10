<?php

namespace Modules\users\Accountant\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Users\Accountant\Services\AccountantApiServiceInterface;
use Modules\Users\Accountant\app\Http\Request\AccountantRequest;
use Modules\Users\Accountant\app\Http\Resource\AccountantResource;

class AccountantController extends Controller
{
    protected AccountantApiServiceInterface $service;

    public function __construct(AccountantApiServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $collection = $this->service->list($request->all());
        return AccountantResource::collection($collection);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['fields' => ['name', 'email', 'password']], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountantRequest $request)
    {
        $user = $this->service->create($request->validated());
        return new AccountantResource($user);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return new AccountantResource($this->service->show((int) $id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return new AccountantResource($this->service->show((int) $id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AccountantRequest $request, $id)
    {
        $user = $this->service->update((int)$id, $request->validated());
        return new AccountantResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->service->delete((int)$id);
        return response()->json(null, 204);
    }
}

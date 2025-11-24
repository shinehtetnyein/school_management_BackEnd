<?php

namespace Modules\Users\Librarian\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Users\Librarian\Services\LibrarianApiServiceInterface;
use Modules\Users\Librarian\app\Http\Request\LibrarianRequest;
use Modules\Users\Librarian\app\Http\Resource\LibrarianResource;

class LibrarianController extends Controller
{
    protected LibrarianApiServiceInterface $service;

    public function __construct(LibrarianApiServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->service->list($request->all());
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
    public function store(LibrarianRequest $request)
    {
        $user = $this->service->create($request->validated());
        return new LibrarianResource($user);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return new LibrarianResource($this->service->show((int) $id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return new LibrarianResource($this->service->show((int) $id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LibrarianRequest $request, $id)
    {
        $user = $this->service->update((int)$id, $request->validated());
        return new LibrarianResource($user);
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

<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Library\Services\LibraryApiServiceInterface;

class LibraryController extends Controller
{
    protected LibraryApiServiceInterface $service;

    public function __construct(LibraryApiServiceInterface $service)
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

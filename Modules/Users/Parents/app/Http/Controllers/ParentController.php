<?php

namespace Modules\Users\Parents\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Users\User\App\Models\User;
use App\Console\Enums\Role;

class ParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $parents = User::whereHas('roles', function ($query) {
            $query->where('name', Role::PARENT->value);
        })->orderBy('id', 'asc')->get();

        return response()->json([
            'message' => 'Parents list',
            'data' => $parents
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Parent created successfully',
            'data' => []
        ], 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        $parent = User::find($id);

        if (!$parent || !$parent->hasRole(Role::PARENT->value)) {
            return response()->json([
                'message' => 'Parent not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Parent details',
            'data' => $parent
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        return response()->json([
            'message' => 'Parent updated successfully',
            'data' => []
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        return response()->json([
            'message' => 'Parent deleted successfully'
        ]);
    }
}

<?php
namespace Modules\ClassRoom\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ClassRoom\app\Models\Section;

class SectionController extends Controller
{
    public function index()
    {
        return Section::all();
    }

    public function show($id)
    {
        return Section::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'classroom_id' => 'required|exists:classroom,id',
            'status' => 'in:active,inactive',
        ]);
        return Section::create($data);
    }

    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|string',
            'classroom_id' => 'sometimes|exists:classroom,id',
            'status' => 'sometimes|in:active,inactive',
        ]);
        $section->update($data);
        return $section;
    }

    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();
        return response()->json(['message' => 'Section deleted']);
    }
}

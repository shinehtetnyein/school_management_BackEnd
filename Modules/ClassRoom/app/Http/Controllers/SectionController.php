<?php
namespace Modules\ClassRoom\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ClassRoom\Services\SectionApiServiceInterface;
use Modules\ClassRoom\app\Http\Requests\StoreSectionRequest;
use Modules\ClassRoom\app\Http\Requests\UpdateSectionRequest;
use Modules\ClassRoom\app\Http\Resources\SectionResource;

class SectionController extends Controller
{
    protected SectionApiServiceInterface $sectionService;

    public function __construct(SectionApiServiceInterface $sectionService)
    {
        $this->sectionService = $sectionService;
    }

    public function index()
    {
        $sections = $this->sectionService->getAllSections();
        return SectionResource::collection($sections);
    }

    public function show($id)
    {
        $section = $this->sectionService->getSectionById($id);
        return new SectionResource($section);
    }

    public function store(StoreSectionRequest $request)
    {
        $section = $this->sectionService->createSection($request->validated());
        return new SectionResource($section);
    }

    public function update(UpdateSectionRequest $request, $id)
    {
        $section = $this->sectionService->updateSection($id, $request->validated());
        return new SectionResource($section);
    }

    public function destroy($id)
    {
        $this->sectionService->deleteSection($id);
        return response()->json(null, 204);
    }
}

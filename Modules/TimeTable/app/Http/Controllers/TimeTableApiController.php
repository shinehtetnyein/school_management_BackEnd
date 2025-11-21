<?php

namespace Modules\TimeTable\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TimeTable\app\Http\Requests\StoreTimeTableRequest;
use Modules\TimeTable\app\Http\Requests\UpdateTimeTableRequest;
use Modules\TimeTable\app\Http\Resources\TimeTableResource;
use Modules\TimeTable\Services\TimeTableApiServiceInterface;

class TimeTableApiController extends Controller
{
    public function __construct(protected TimeTableApiServiceInterface $timeTableService) {}

    /**
     * Display a listing of timetable entries.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'classroom_id' => $request->query('classroom_id'),
                'section_id' => $request->query('section_id'),
                'course_id' => $request->query('course_id'),
                'day_of_week' => $request->query('day_of_week'),
                'teacher_id' => $request->query('teacher_id'),
                'status' => $request->query('status', 'active'),
            ];

            // Remove null values from filters
            $filters = array_filter($filters, fn($value) => $value !== null);

            $relations = ['classroom', 'section', 'course', 'subject', 'teacher'];
            $limit = $request->query('limit');
            $noPagination = $request->query('no_pagination', false);
            $pagPerPage = $request->query('per_page', 15);

            $timeTables = $this->timeTableService->getAll(
                $relations,
                $limit,
                null,
                $noPagination,
                $pagPerPage,
                $filters
            );

            return apiResponse(true, 'Timetables retrieved successfully', [
                'data' => TimeTableResource::collection($timeTables),
                'pagination' => $timeTables instanceof \Illuminate\Pagination\AbstractPaginator ? [
                    'total' => $timeTables->total(),
                    'per_page' => $timeTables->perPage(),
                    'current_page' => $timeTables->currentPage(),
                    'last_page' => $timeTables->lastPage(),
                ] : null,
            ]);
        } catch (\Exception $e) {
            return apiResponse(false, 'Failed to retrieve timetables', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created timetable entry.
     */
    public function store(StoreTimeTableRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $timeTable = $this->timeTableService->create($data);

            return apiResponse(true, 'Timetable created successfully', new TimeTableResource($timeTable->load(['classroom', 'section', 'course', 'subject', 'teacher'])), 201);
        } catch (\Exception $e) {
            return apiResponse(false, 'Failed to create timetable', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified timetable entry.
     */
    public function show($id): JsonResponse
    {
        try {
            $timeTable = $this->timeTableService->get($id, ['classroom', 'section', 'course', 'subject', 'teacher']);

            if (!$timeTable) {
                return apiResponse(false, 'Timetable not found', null, 404);
            }

            return apiResponse(true, 'Timetable retrieved successfully', new TimeTableResource($timeTable));
        } catch (\Exception $e) {
            return apiResponse(false, 'Failed to retrieve timetable', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified timetable entry.
     */
    public function update(UpdateTimeTableRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $timeTable = $this->timeTableService->update($id, $data);

            return apiResponse(true, 'Timetable updated successfully', new TimeTableResource($timeTable->load(['classroom', 'section', 'course', 'subject', 'teacher'])));
        } catch (\Exception $e) {
            return apiResponse(false, 'Failed to update timetable', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified timetable entry.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->timeTableService->delete($id);

            return apiResponse(true, 'Timetable deleted successfully', null);
        } catch (\Exception $e) {
            return apiResponse(false, 'Failed to delete timetable', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get timetable by classroom and day.
     */
    public function getByClassroomAndDay(Request $request): JsonResponse
    {
        try {
            $classroomId = $request->query('classroom_id');
            $dayOfWeek = $request->query('day_of_week');

            if (!$classroomId || !$dayOfWeek) {
                return apiResponse(false, 'classroom_id and day_of_week are required', null, 400);
            }

            $timeTables = $this->timeTableService->getByClassroomAndDay(
                $classroomId,
                $dayOfWeek,
                ['classroom', 'section', 'course', 'subject', 'teacher']
            );

            return apiResponse(true, 'Timetables retrieved successfully', TimeTableResource::collection($timeTables));
        } catch (\Exception $e) {
            return apiResponse(false, 'Failed to retrieve timetables', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get timetable by teacher.
     */
    public function getByTeacher(Request $request): JsonResponse
    {
        try {
            $teacherId = $request->query('teacher_id');

            if (!$teacherId) {
                return apiResponse(false, 'teacher_id is required', null, 400);
            }

            $timeTables = $this->timeTableService->getByTeacher(
                $teacherId,
                ['classroom', 'section', 'course', 'subject', 'teacher']
            );

            return apiResponse(true, 'Timetables retrieved successfully', TimeTableResource::collection($timeTables));
        } catch (\Exception $e) {
            return apiResponse(false, 'Failed to retrieve timetables', ['error' => $e->getMessage()], 500);
        }
    }
}

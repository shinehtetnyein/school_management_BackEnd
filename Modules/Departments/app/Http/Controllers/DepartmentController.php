<?php

namespace Modules\Departments\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Departments\app\Http\Requests\DepartmentRequest;
use Modules\Departments\app\Http\Requests\EventRequest;
use Modules\Departments\app\Http\Requests\AnnouncementRequest;
use Modules\Departments\app\Http\Requests\ScheduleRequest;
use Modules\Users\User\App\Models\User as UserModel;
use Modules\Departments\Services\DepartmentApiServiceInterface;

class DepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentApiServiceInterface $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index(): JsonResponse
    {
        $departments = $this->departmentService->getAllDepartments();
        return response()->json($departments);
    }

    public function show(int $id): JsonResponse
    {
        $department = $this->departmentService->getDepartmentById($id);
        return response()->json($department);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments,code',
            'type' => 'required|string|in:academic,administrative,support',
            'description' => 'nullable|string',
            'head_of_department' => 'nullable|exists:users,id',
            'location' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'grade_levels' => 'nullable|array',
            'subjects' => 'nullable|array',
            'office_hours' => 'nullable|json'
        ]);

        $department = $this->departmentService->createDepartment($validated);
        return response()->json($department, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'code' => 'string|unique:departments,code,' . $id,
            'type' => 'string|in:academic,administrative,support',
            'description' => 'nullable|string',
            'head_of_department' => 'nullable|exists:users,id',
            'location' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'grade_levels' => 'nullable|array',
            'subjects' => 'nullable|array',
            'office_hours' => 'nullable|json'
        ]);

        $department = $this->departmentService->updateDepartment($id, $validated);
        return response()->json($department);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->departmentService->deleteDepartment($id);
        return response()->json(null, 204);
    }

    // Department Events Management
    public function events(int $id): JsonResponse
    {
        $events = $this->departmentService->getDepartmentEvents($id);
        return response()->json($events);
    }

    public function createEvent(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string',
            'event_type' => 'required|string',
            'grade_level' => 'nullable|array',
            'max_participants' => 'nullable|integer|min:1',
            'requires_permission' => 'boolean',
            'additional_info' => 'nullable|array'
        ]);

        $event = $this->departmentService->createDepartmentEvent($id, $validated);
        return response()->json($event, 201);
    }

    // Department Announcements
    public function announcements(int $id): JsonResponse
    {
        $announcements = $this->departmentService->getDepartmentAnnouncements($id);
        return response()->json($announcements);
    }

    public function createAnnouncement(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:publish_date',
            'priority' => 'integer|min:0|max:10',
            'category' => 'required|string',
            'target_audience' => 'required|string',
            'grade_level' => 'nullable|array',
            'requires_acknowledgment' => 'boolean',
            'attachments' => 'nullable|array'
        ]);

        $announcement = $this->departmentService->createDepartmentAnnouncement($id, $validated);
        return response()->json($announcement, 201);
    }

    // Department Schedules
    public function schedules(int $id): JsonResponse
    {
        $schedules = $this->departmentService->getDepartmentSchedules($id);
        return response()->json($schedules);
    }

    public function createSchedule(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_uuid' => 'required|exists:users,uuid',
            'grade_level' => 'required|string',
            'section' => 'required|string',
            'day_of_week' => 'required|integer|min:0|max:6',
            'period_type' => 'required|string',
            'period_number' => 'required|integer|min:1',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'required|string',
            'attendance_required' => 'boolean',
            'substitution_teacher_uuid' => 'nullable|exists:users,uuid'
        ]);

        // Resolve teacher UUIDs to internal numeric IDs for storage
        $teacher = UserModel::where('uuid', $validated['teacher_uuid'])->firstOrFail();
        $validated['teacher_id'] = $teacher->id;
        unset($validated['teacher_uuid']);

        if (!empty($validated['substitution_teacher_uuid'])) {
            $sub = UserModel::where('uuid', $validated['substitution_teacher_uuid'])->firstOrFail();
            $validated['substitution_teacher_id'] = $sub->id;
            unset($validated['substitution_teacher_uuid']);
        }

        $schedule = $this->departmentService->createDepartmentSchedule($id, $validated);
        return response()->json($schedule, 201);
    }

    // Department Statistics and Reports
    public function statistics(int $id): JsonResponse
    {
        $stats = $this->departmentService->getDepartmentStatistics($id);
        return response()->json($stats);
    }

    public function teacherWorkload(int $id): JsonResponse
    {
        $workload = $this->departmentService->getTeacherWorkload($id);
        return response()->json($workload);
    }

    public function resourceUtilization(int $id): JsonResponse
    {
        $utilization = $this->departmentService->getResourceUtilization($id);
        return response()->json($utilization);
    }

    // Academic Calendar
    public function academicCalendar(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        $calendar = $this->departmentService->getAcademicCalendar(
            $id,
            $validated['start_date'],
            $validated['end_date']
        );

        return response()->json($calendar);
    }

    // Performance Metrics
    public function performanceMetrics(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        $metrics = $this->departmentService->getPerformanceMetrics(
            $id,
            $validated['start_date'],
            $validated['end_date']
        );

        return response()->json($metrics);
    }
}

<?php

namespace Modules\Departments\Services\Implementations;

use Carbon\Carbon;
use Modules\Departments\Models\Department;
use Modules\Departments\Services\DepartmentApiServiceInterface;
use Modules\Users\Models\User;

class DepartmentApiService implements DepartmentApiServiceInterface
{
    // Basic CRUD Operations
    public function getAllDepartments(?string $type = null): array
    {
        $query = Department::query();
        if ($type) {
            $query->where('type', $type);
        }
        return $query->orderBy('id', 'asc')->get()->toArray();
    }

    public function getDepartmentById(int $id): array
    {
        return Department::findOrFail($id)->toArray();
    }

    public function createDepartment(array $data): array
    {
        return Department::create($data)->fresh()->toArray();
    }

    public function updateDepartment(int $id, array $data): array
    {
        $department = Department::findOrFail($id);
        $department->update($data);
        return $department->fresh()->toArray();
    }

    public function deleteDepartment(int $id): bool
    {
        return Department::findOrFail($id)->delete();
    }

    // High School Specific Methods
    public function getDepartmentGradeLevelInfo(int $id, string $grade): array
    {
        $department = Department::findOrFail($id);
        return [
            'teachers' => $department->teachers()
                ->whereHas('classes', function ($query) use ($grade) {
                    $query->where('grade_level', $grade);
                })->get()->toArray(),
            'subjects' => $department->subjects()
                ->whereHas('classes', function ($query) use ($grade) {
                    $query->where('grade_level', $grade);
                })->get()->toArray(),
            'schedules' => $department->schedules()
                ->where('grade_level', $grade)
                ->get()->toArray()
        ];
    }

    public function getTeacherAssignments(int $id): array
    {
        $department = Department::findOrFail($id);
        return $department->teachers()
            ->with(['classes', 'subjects'])
            ->get()
            ->map(fn($teacher) => [
                'teacher' => $teacher->toArray(),
                'assignments' => $teacher->classes->groupBy('grade_level')
            ])
            ->toArray();
    }

    public function getClassSchedule(int $id, string $grade, string $section): array
    {
        return Department::findOrFail($id)
            ->schedules()
            ->where('grade_level', $grade)
            ->where('section', $section)
            ->orderBy('day_of_week')
            ->orderBy('period_number')
            ->get()
            ->toArray();
    }

    public function getAvailableSubstituteTeachers(int $id): array
    {
        $department = Department::findOrFail($id);
        return $department->teachers()
            ->whereDoesntHave('schedules', function ($query) {
                $query->where('date', now()->toDateString());
            })
            ->get()
            ->toArray();
    }

    public function getParentNotifications(int $id, string $grade): array
    {
        $department = Department::findOrFail($id);
        return $department->announcements()
            ->where(function ($query) use ($grade) {
                $query->whereJsonContains('grade_level', $grade)
                    ->orWhereNull('grade_level');
            })
            ->where('target_audience', 'parents')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    // Event Management
    public function getDepartmentEvents(int $id, ?string $type = null, ?string $grade = null): array
    {
        $query = Department::findOrFail($id)->events();

        if ($type) {
            $query->where('event_type', $type);
        }

        if ($grade) {
            $query->whereJsonContains('grade_level', $grade);
        }

        return $query->orderBy('start_date')->get()->toArray();
    }

    public function createDepartmentEvent(int $id, array $data): array
    {
        $department = Department::findOrFail($id);
        return $department->events()->create($data)->fresh()->toArray();
    }

    // Announcement Management
    public function getDepartmentAnnouncements(int $id, ?string $category = null, ?string $audience = null, ?string $grade = null): array
    {
        $query = Department::findOrFail($id)->announcements();

        if ($category) {
            $query->where('category', $category);
        }

        if ($audience) {
            $query->where('target_audience', $audience);
        }

        if ($grade) {
            $query->whereJsonContains('grade_level', $grade);
        }

        return $query->orderBy('publish_date', 'desc')->get()->toArray();
    }

    public function createDepartmentAnnouncement(int $id, array $data): array
    {
        $department = Department::findOrFail($id);
        return $department->announcements()->create($data)->fresh()->toArray();
    }

    // Schedule Management
    public function getDepartmentSchedules(int $id, array $filters = []): array
    {
        $query = Department::findOrFail($id)->schedules();

        foreach ($filters as $key => $value) {
            if ($value !== null) {
                $query->where($key, $value);
            }
        }

        return $query->orderBy('day_of_week')
            ->orderBy('period_number')
            ->get()
            ->toArray();
    }

    public function createDepartmentSchedule(int $id, array $data): array
    {
        $department = Department::findOrFail($id);
        return $department->schedules()->create($data)->fresh()->toArray();
    }

    // Statistics and Reports
    public function getDepartmentStatistics(int $id): array
    {
        $department = Department::findOrFail($id);
        return [
            'teacher_count' => $department->teachers()->count(),
            'active_subjects' => $department->subjects()->where('status', true)->count(),
            'total_students' => $department->getStudentCount(),
            'resource_utilization' => $this->getResourceUtilization($id),
            'average_attendance' => $department->getAverageAttendance()
        ];
    }

    public function getTeacherWorkload(int $id): array
    {
        $department = Department::findOrFail($id);
        return $department->teachers()
            ->with(['classes', 'subjects'])
            ->get()
            ->map(function ($teacher) {
                return [
                    'name' => $teacher->name,
                    'subjects' => $teacher->subjects->map(function ($subject) {
                        return [
                            'name' => $subject->name,
                            'grade_level' => $subject->pivot->grade_level,
                            'hours_per_week' => $subject->credit_hours
                        ];
                    }),
                    'total_hours' => $teacher->subjects->sum('credit_hours')
                ];
            })
            ->toArray();
    }

    public function getResourceUtilization(int $id): array
    {
        $department = Department::findOrFail($id);
        return [
            'classrooms' => $department->classrooms()->with('schedules')->get()->map(function ($classroom) {
                return [
                    'name' => $classroom->name,
                    'usage_percentage' => $classroom->getUsagePercentage()
                ];
            })->toArray(),
            'equipment' => $department->equipment()->with('maintenanceLogs')->get()->map(function ($equipment) {
                return [
                    'name' => $equipment->name,
                    'condition' => $equipment->getConditionStatus(),
                    'last_maintenance' => $equipment->lastMaintenance?->format('Y-m-d')
                ];
            })->toArray()
        ];
    }

    public function getAcademicCalendar(int $id, string $startDate, string $endDate): array
    {
        $department = Department::findOrFail($id);
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        return [
            'events' => $department->events()
                ->whereBetween('start_date', [$start, $end])
                ->orderBy('start_date')
                ->get()
                ->toArray(),
            'schedules' => $department->schedules()
                ->whereBetween('date', [$start, $end])
                ->orderBy('date')
                ->orderBy('period_number')
                ->get()
                ->toArray(),
            'exams' => $department->exams()
                ->whereBetween('date', [$start, $end])
                ->orderBy('date')
                ->get()
                ->toArray()
        ];
    }

    public function getPerformanceMetrics(int $id, string $startDate, string $endDate): array
    {
        $department = Department::findOrFail($id);
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        return [
            'attendance' => $department->getAverageAttendance($start, $end),
            'grades' => $department->getAverageGrades($start, $end),
            'teacher_performance' => $department->getTeacherPerformance($start, $end),
            'resource_efficiency' => $department->getResourceEfficiency($start, $end)
        ];
    }

    // Academic Performance and Attendance
    public function getAcademicPerformance(int $id, array $filters): array
    {
        $department = Department::findOrFail($id);
        return $department->getAcademicPerformance($filters);
    }

    public function getAttendanceReport(int $id, array $filters): array
    {
        $department = Department::findOrFail($id);
        return $department->getAttendanceReport($filters);
    }

    public function generateDepartmentReport(int $id, array $filters): array
    {
        $department = Department::findOrFail($id);
        return $department->generateReport($filters);
    }

    // Resource Management
    public function getDepartmentResources(int $id): array
    {
        return Department::findOrFail($id)
            ->resources()
            ->with('maintenanceLogs')
            ->get()
            ->toArray();
    }

    public function assignDepartmentResource(int $id, array $data): array
    {
        $department = Department::findOrFail($id);
        $resource = $department->resources()->findOrFail($data['resource_id']);
        return $resource->assignTo($data['teacher_id'], $data)->toArray();
    }

    public function getSubjectsBySemester(int $id): array
    {
        $department = Department::findOrFail($id);
        return $department->subjects()
            ->with(['teachers', 'classes'])
            ->get()
            ->groupBy('semester')
            ->map(function ($subjects) {
                return $subjects->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'code' => $subject->code,
                        'credit_hours' => $subject->credit_hours,
                        'teachers' => $subject->teachers->map(fn($t) => $t->only(['id', 'name'])),
                        'classes' => $subject->classes->map(fn($c) => [
                            'grade' => $c->grade_level,
                            'section' => $c->section
                        ])
                    ];
                });
            })
            ->toArray();
    }
}

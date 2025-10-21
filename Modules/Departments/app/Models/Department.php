<?php

namespace Modules\Departments\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Modules\Subjects\Models\Subject;
use Modules\Users\Models\User;
use Modules\Courses\Models\Course;

class Department extends Model
{
    protected $table = 'departments';

    const TYPE_ACADEMIC = 'academic';
    const TYPE_ADMINISTRATIVE = 'administrative';
    const TYPE_SUPPORT = 'support';

    protected $fillable = [
        'name',
        'code',
        'description',
        'head_of_department',
        'type', // academic, administrative, support
        'status',
        'establishment_date',
        'contact_email',
        'contact_phone',
        'location'
    ];

    protected $casts = [
        'status' => 'boolean',
        'establishment_date' => 'date'
    ];

    protected $appends = [
        'student_count',
        'teacher_count',
        'course_count',
        'upcoming_events_count'
    ];

    protected static function boot()
    {
        parent::boot();

        // When a department is deleted, set its subjects' department_id to null
        static::deleting(function ($department) {
            $department->subjects()->update(['department_id' => null]);
            $department->events()->delete();
            $department->announcements()->delete();
            $department->schedules()->delete();
        });
    }

    // Relationships
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function headOfDepartment(): BelongsTo
    {
        return $this->belongsTo(User::class, 'head_of_department')
            ->withDefault(['name' => 'Not Assigned']);
    }

    public function courses(): HasManyThrough
    {
        return $this->hasManyThrough(Course::class, Subject::class);
    }

    public function teachers()
    {
        return User::whereHas('teachingSubjects', function($query) {
            $query->where('subjects.department_id', $this->id);
        });
    }

    public function students()
    {
        return User::whereHas('enrolledCourses', function($query) {
            $query->whereHas('subject', function($q) {
                $q->where('department_id', $this->id);
            });
        });
    }

    // Accessors
    public function getStudentCountAttribute(): int
    {
        return $this->students()->count();
    }

    public function getTeacherCountAttribute(): int
    {
        return $this->teachers()->count();
    }

    public function getCourseCountAttribute(): int
    {
        return $this->courses()->count();
    }

    // Helper methods
    public function getSubjectCount(): int
    {
        return $this->subjects()->count();
    }

    public function getActiveSubjects()
    {
        return $this->subjects()->where('status', true)->get();
    }

    public function getStatistics(): array
    {
        $courses = $this->courses;
        $totalStudents = $this->student_count;
        $examsPassed = 0;
        $totalExams = 0;

        foreach ($courses as $course) {
            foreach ($course->exams as $exam) {
                $totalExams++;
                $examsPassed += $exam->results()
                    ->where('marks', '>=', $exam->passing_marks)
                    ->count();
            }
        }

        return [
            'overview' => [
                'total_subjects' => $this->getSubjectCount(),
                'active_subjects' => $this->subjects()->where('status', true)->count(),
                'total_courses' => $this->course_count,
                'total_teachers' => $this->teacher_count,
                'total_students' => $totalStudents
            ],
            'academic' => [
                'total_exams' => $totalExams,
                'pass_rate' => $totalExams ? round(($examsPassed / $totalExams) * 100, 2) : 0,
                'active_courses' => $this->courses()->where('status', true)->count()
            ],
            'staff' => [
                'head_of_department' => $this->headOfDepartment->name,
                'active_teachers' => $this->teachers()->where('status', true)->count()
            ],
            'subjects_by_semester' => $this->getSubjectsBySemester()
        ];
    }

    public function getSubjectsBySemester(): array
    {
        return $this->subjects()
            ->with(['courses' => function($query) {
                $query->whereHas('academicYear', function($q) {
                    $q->where('is_current', true);
                });
            }])
            ->get()
            ->groupBy('semester')
            ->map(function($subjects) {
                return $subjects->map(function($subject) {
                    return [
                        'name' => $subject->name,
                        'code' => $subject->code,
                        'credit_hours' => $subject->credit_hours,
                        'active_courses' => $subject->courses->count()
                    ];
                });
            })
            ->toArray();
    }

    public function getTeacherWorkload(): array
    {
        return $this->teachers()
            ->with(['teachingSubjects' => function($query) {
                $query->where('department_id', $this->id);
            }])
            ->get()
            ->map(function($teacher) {
                return [
                    'teacher_name' => $teacher->name,
                    'subjects_count' => $teacher->teachingSubjects->count(),
                    'total_credit_hours' => $teacher->teachingSubjects->sum('credit_hours'),
                    'subjects' => $teacher->teachingSubjects->pluck('name')
                ];
            })
            ->toArray();
    }

    // Events Management
    public function events(): MorphMany
    {
        return $this->morphMany(Event::class, 'eventable');
    }

    public function announcements(): MorphMany
    {
        return $this->morphMany(Announcement::class, 'announceable');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(DepartmentSchedule::class);
    }

    public function getUpcomingEventsCountAttribute(): int
    {
        return $this->events()
            ->where('start_date', '>=', now())
            ->count();
    }

    // Academic Calendar Management
    public function createAcademicCalendar(Carbon $startDate, Carbon $endDate): array
    {
        $period = CarbonPeriod::create($startDate, $endDate);
        $calendar = [];

        foreach ($period as $date) {
            $dayEvents = $this->getDayEvents($date);
            if (!empty($dayEvents)) {
                $calendar[$date->format('Y-m-d')] = $dayEvents;
            }
        }

        return $calendar;
    }

    protected function getDayEvents(Carbon $date): array
    {
        $events = [];

        // Get regular events
        $dayEvents = $this->events()
            ->whereDate('start_date', $date)
            ->get();

        foreach ($dayEvents as $event) {
            $events[] = [
                'type' => 'event',
                'title' => $event->title,
                'time' => $event->start_date->format('H:i'),
                'location' => $event->location
            ];
        }

        // Get class schedules
        $schedules = $this->schedules()
            ->where('day_of_week', $date->dayOfWeek)
            ->get();

        foreach ($schedules as $schedule) {
            $events[] = [
                'type' => 'class',
                'title' => $schedule->subject->name,
                'time' => $schedule->start_time,
                'location' => $schedule->room
            ];
        }

        return $events;
    }

    // Department Resource Management
    public function assignResources(array $resources): bool
    {
        try {
            foreach ($resources as $resource) {
                DepartmentResource::create([
                    'department_id' => $this->id,
                    'name' => $resource['name'],
                    'type' => $resource['type'],
                    'quantity' => $resource['quantity'] ?? 1,
                    'status' => $resource['status'] ?? true
                ]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getResourceUtilization(): array
    {
        $resources = DepartmentResource::where('department_id', $this->id)
            ->get()
            ->groupBy('type');

        $utilization = [];

        foreach ($resources as $type => $items) {
            $utilization[$type] = [
                'total' => $items->sum('quantity'),
                'available' => $items->where('status', true)->sum('quantity'),
                'in_use' => $items->where('status', false)->sum('quantity'),
                'items' => $items->map(fn($item) => [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'status' => $item->status ? 'Available' : 'In Use'
                ])
            ];
        }

        return $utilization;
    }

    // Department Performance Metrics
    public function getPerformanceMetrics(Carbon $startDate, Carbon $endDate): array
    {
        $courses = $this->courses()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $examResults = collect();
        foreach ($courses as $course) {
            $examResults = $examResults->concat($course->results);
        }

        return [
            'academic' => [
                'average_pass_rate' => $this->calculateAveragePassRate($examResults),
                'grade_distribution' => $this->calculateGradeDistribution($examResults),
                'subject_performance' => $this->calculateSubjectPerformance($courses)
            ],
            'operational' => [
                'resource_utilization' => $this->getResourceUtilization(),
                'teacher_attendance' => $this->calculateTeacherAttendance($startDate, $endDate),
                'course_completion_rate' => $this->calculateCourseCompletionRate($courses)
            ],
            'timeline' => [
                'events_completed' => $this->events()
                    ->whereBetween('start_date', [$startDate, $endDate])
                    ->where('status', 'completed')
                    ->count(),
                'upcoming_events' => $this->upcoming_events_count,
                'schedule_adherence' => $this->calculateScheduleAdherence($startDate, $endDate)
            ]
        ];
    }

    protected function calculateAveragePassRate(Collection $results): float
    {
        if ($results->isEmpty()) return 0;

        $passCount = $results->filter(fn($result) => $result->status === 'Pass')->count();
        return ($passCount / $results->count()) * 100;
    }

    protected function calculateGradeDistribution(Collection $results): array
    {
        $distribution = [
            'A+' => 0, 'A' => 0, 'B' => 0,
            'C' => 0, 'D' => 0, 'F' => 0
        ];

        foreach ($results as $result) {
            $distribution[$result->letter_grade]++;
        }

        return $distribution;
    }

    protected function calculateSubjectPerformance(Collection $courses): array
    {
        return $courses->groupBy('subject_id')
            ->map(function($coursesGroup) {
                $subject = $coursesGroup->first()->subject;
                $results = collect();

                foreach ($coursesGroup as $course) {
                    $results = $results->concat($course->results);
                }

                return [
                    'subject_name' => $subject->name,
                    'average_score' => $results->avg('marks'),
                    'pass_rate' => $this->calculateAveragePassRate($results)
                ];
            })
            ->values()
            ->toArray();
    }

    protected function calculateTeacherAttendance(Carbon $startDate, Carbon $endDate): array
    {
        $teachers = $this->teachers()->get();
        $attendance = [];

        foreach ($teachers as $teacher) {
            $totalClasses = $teacher->schedules()
                ->whereBetween('date', [$startDate, $endDate])
                ->count();

            $attendedClasses = $teacher->schedules()
                ->whereBetween('date', [$startDate, $endDate])
                ->where('status', 'attended')
                ->count();

            $attendance[$teacher->name] = [
                'total_classes' => $totalClasses,
                'attended' => $attendedClasses,
                'attendance_rate' => $totalClasses ? ($attendedClasses / $totalClasses) * 100 : 0
            ];
        }

        return $attendance;
    }

    protected function calculateCourseCompletionRate(Collection $courses): float
    {
        if ($courses->isEmpty()) return 0;

        $completedCourses = $courses->filter(fn($course) =>
            $course->end_date && $course->end_date < now()
        )->count();

        return ($completedCourses / $courses->count()) * 100;
    }

    protected function calculateScheduleAdherence(Carbon $startDate, Carbon $endDate): float
    {
        $scheduledEvents = $this->schedules()
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        if (!$scheduledEvents) return 0;

        $completedAsScheduled = $this->schedules()
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'completed_on_schedule')
            ->count();

        return ($completedAsScheduled / $scheduledEvents) * 100;
    }
}

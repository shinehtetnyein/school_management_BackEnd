<?php

namespace Modules\ClassRoom\app\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClassroomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Build resource with the fields you requested:
        // room_name, sections (names), teachers (unique list), days, no_of_students, no_of_subjects, course_name

        // room name
        $roomName = $this->room_number ?? data_get($this, 'room_number') ?? null;

        // ensure related models are loaded from DB if not already
        $model = $this->resource;
        if ($model instanceof Model) {
            $model->loadMissing(['sections', 'timetables.teacher', 'timetables.subject', 'timetables.course', 'timetables.section', 'students']);
        }

        // sections (names) - ensure unique and ordered
        $sectionNames = $model->sections->pluck('name')->filter()->unique()->sort()->values()->all();

        // Timetables (now guaranteed to be available via loadMissing)
        $timetables = $model->timetables ?? collect();

        // teachers: unique list from timetables
        $teachers = collect($timetables)->flatMap(function ($tt) {
            $teacher = data_get($tt, 'teacher') ?? (is_object($tt) && isset($tt->teacher) ? $tt->teacher : null);
            if ($teacher) {
                return [[
                    'id' => $teacher->id ?? data_get($teacher, 'id'),
                    'name' => $teacher->name ?? data_get($teacher, 'name'),
                    'email' => $teacher->email ?? data_get($teacher, 'email'),
                ]];
            }
            return [];
        })->unique('id')->values()->all();

        // days: unique ordered days from timetables (Monday..Friday priority)
        $days = collect($timetables)->pluck('day_of_week')->filter()->unique()->values()->all();

        // number of students (class total)
        $noOfStudents = $this->students_count ?? $this->students()->count();

        // number of subjects: distinct subject ids in timetables
        $noOfSubjects = collect($timetables)->pluck('subject_id')->filter()->unique()->count();

        // course_name: unique course names from timetables
        $courseNames = collect($timetables)->map(function ($tt) {
            return data_get($tt, 'course.course_name') ?? data_get($tt, 'course_name') ?? null;
        })->filter()->unique()->values()->all();

        return [
            'id' => $this->id ?? null,
            'room_name' => $roomName,
            'sections' => $sectionNames,
            'teachers' => $teachers,
            'days' => $days,
            // active_now: true if any timetable entry for this classroom is active now
            'active_now' => $this->computeActiveNow($timetables),
            'no_of_students' => (int) ($noOfStudents ?? 0),
            'no_of_subjects' => (int) $noOfSubjects,
            'course_name' => $courseNames,
        ];
    }

    /**
     * Determine if any timetable entry is active now for the classroom.
     */
    protected function computeActiveNow($timetables): bool
    {
        try {
            $now = Carbon::now();
            $cutoff = Carbon::today()->setTime(15, 0, 0);
            if ($now->greaterThanOrEqualTo($cutoff)) {
                return false;
            }

            foreach ($timetables as $tt) {
                $startVal = data_get($tt, 'start_time') ?? (is_object($tt) && isset($tt->start_time) ? $tt->start_time : null);
                $endVal = data_get($tt, 'end_time') ?? (is_object($tt) && isset($tt->end_time) ? $tt->end_time : null);
                if (!$startVal || !$endVal) continue;

                try {
                    $start = Carbon::parse($startVal);
                    $end = Carbon::parse($endVal);
                } catch (\Exception $e) {
                    continue;
                }

                if ($now->greaterThanOrEqualTo($start) && $now->lessThan($end)) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}

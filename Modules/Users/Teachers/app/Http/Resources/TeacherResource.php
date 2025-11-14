<?php

namespace Modules\Users\Teachers\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->getFullName(),
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'profile_photo' => $this->profile_photo,
            'status' => $this->status,
            'roles' => $this->roles->map(fn($role) => $role->name)->toArray(),
            // 'permissions' => $this->getAllPermissions()->map(fn($perm) => $perm->name)->toArray(),
            'teaching_subjects' => $this->whenLoaded('teachingSubjects', function() {
                return $this->teachingSubjects->map(function($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->subject_name,
                        'code' => $subject->subject_code ?? null,
                        'subject_desc' => $subject->subject_desc ?? null,
                        'class_level' => $subject->class_level ?? null,
                        'status' => $subject->pivot->status,
                        'assigned_date' => $subject->pivot->assigned_date,
                    ];
                });
            }),
            'teaching_courses' => $this->whenLoaded('teachingCourses', function() {
                return $this->teachingCourses->map(function($course) {
                    return [
                        'id' => $course->id,
                        'name' => $course->course_name,
                        'category' => $course->category ?? null,
                        'description' => $course->description ?? null,
                        'status' => $course->pivot->status,
                        'assigned_date' => $course->pivot->assigned_date,
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

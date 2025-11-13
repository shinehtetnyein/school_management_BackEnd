<?php
namespace Modules\Users\Students\app\Http\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'role' => $this->role,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'profile_photo' => $this->profile_photo,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
                'classrooms' => $this->classroom->map(fn($c) => [
                    'id' => $c->id,
                    'room_number' => $c->room_number ?? null,
                    'building' => $c->building ?? null,
                    'room_type' => $c->room_type ?? null,
                ]),
                'sections' => $this->section->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->name ?? null,
                ]),
                'subjects' => $this->subjects->map(fn($sub) => [
                    'id' => $sub->id,
                    'subject_code' => $sub->subject_code ?? null,
                    'subject_name' => $sub->subject_name ?? null,
                ]),
                'courses' => $this->courses->map(fn($course) => [
                    'id' => $course->id,
                    'course_code' => $course->course_code ?? null,
                    'course_name' => $course->course_name ?? null,
                    'description' => $course->description ?? null,
                ]),
        ];
    }
}

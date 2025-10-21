<?php

namespace Modules\Departments\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:publish_date',
            'priority' => 'integer|min:0|max:10',
            'category' => 'required|string|in:academic,administrative,events,emergency,extracurricular,general',
            'target_audience' => 'required|string|in:all,students,parents,teachers,staff',
            'grade_level' => 'nullable|array',
            'grade_level.*' => 'string',
            'requires_acknowledgment' => 'boolean',
            'attachments' => 'nullable|array',
            'attachments.*.name' => 'required|string',
            'attachments.*.url' => 'required|url'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Announcement title is required',
            'content.required' => 'Announcement content is required',
            'publish_date.required' => 'Publish date is required',
            'expiry_date.after' => 'Expiry date must be after publish date',
            'priority.between' => 'Priority must be between 0 and 10',
            'category.required' => 'Announcement category is required',
            'category.in' => 'Invalid announcement category selected',
            'target_audience.required' => 'Target audience is required',
            'target_audience.in' => 'Invalid target audience selected'
        ];
    }
}

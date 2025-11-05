<?php
// Modules/Academic/app/Http/Requests/StoreAcademicYearRequest.php

namespace Modules\AcademicYears\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year_name' => 'required|string|max:255|unique:academic_years,year_name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_current' => 'boolean',
            'status' => 'required|in:active,inactive,completed',
            'description' => 'nullable|string',
            'created_by' => 'required|exists:users,id',
            'updated_by' => 'required|exists:users,id'
        ];
    }

    public function messages(): array
    {
        return [
            'year_name.unique' => 'An academic year with this name already exists.',
            'end_date.after' => 'The end date must be after the start date.',
        ];
    }
}

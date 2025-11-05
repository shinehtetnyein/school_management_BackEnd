<?php
// Modules/Academic/app/Http/Requests/UpdateAcademicYearRequest.php

namespace Modules\AcademicYears\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $academicYearId = $this->route('year') ?? $this->route('id');

        return [
            'year_name' => 'sometimes|required|string|max:255|unique:academic_years,year_name,' . $academicYearId,
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'is_current' => 'boolean',
            'status' => 'sometimes|required|in:active,inactive,completed',
            'description' => 'nullable|string',
            'updated_by' => 'required|exists:users,id'
        ];
    }
}

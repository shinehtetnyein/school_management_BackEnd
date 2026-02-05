<?php

namespace Modules\ClassRoom\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'classroom_id' => 'sometimes|exists:class_rooms,id',
            'status' => 'nullable|string|max:255',
        ];
    }
}

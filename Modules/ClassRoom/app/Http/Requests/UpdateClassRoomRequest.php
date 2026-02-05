<?php

namespace Modules\ClassRoom\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassRoomRequest extends FormRequest
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
            'room_number' => 'sometimes|string|max:255',
            'building' => 'nullable|string|max:255',
            'room_type' => 'nullable|string|max:255',
        ];
    }
}

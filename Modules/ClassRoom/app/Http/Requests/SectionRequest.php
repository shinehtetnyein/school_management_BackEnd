<?php
namespace Modules\ClassRoom\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'classroom_id' => 'required|exists:classroom,id',
            'status' => 'in:active,inactive',
        ];
    }
}

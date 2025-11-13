<?php
namespace Modules\Users\Students\app\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use App\Console\Enums\Role;

class StudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'role' => 'in:' . Role::STUDENT->value,
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'profile_photo' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'status' => 'nullable|in:active,inactive,suspended',
        ];
    }
}

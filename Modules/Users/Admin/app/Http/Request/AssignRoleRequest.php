<?php
// Modules/Users/Admin/app/Http/Request/AssignRoleRequest.php

namespace Modules\Users\Admin\App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role' => 'required|string|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [
            'role.exists' => 'The selected role does not exist.',
        ];
    }
}

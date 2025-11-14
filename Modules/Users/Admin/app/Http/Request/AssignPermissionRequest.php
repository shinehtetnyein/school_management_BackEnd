<?php
// Modules/Users/Admin/app/Http/Request/AssignPermissionRequest.php

namespace Modules\Users\Admin\App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permission' => 'required|string|exists:permissions,name',
        ];
    }

    public function messages(): array
    {
        return [
            'permission.exists' => 'The selected permission does not exist.',
        ];
    }
}

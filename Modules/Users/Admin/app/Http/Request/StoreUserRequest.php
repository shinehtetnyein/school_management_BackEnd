<?php
// Modules/Users/Admin/app/Http/Request/StoreUserRequest.php

namespace Modules\Users\Admin\App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_no' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'status' => 'nullable|in:active,inactive',
            'role' => 'nullable|string|exists:roles,name',
        ];
    }
}

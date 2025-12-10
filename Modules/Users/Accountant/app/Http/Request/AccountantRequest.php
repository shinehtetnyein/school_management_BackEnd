<?php

namespace Modules\Users\Accountant\app\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class AccountantRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('accountant') ?? $this->route('id');

        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes', 'required', 'email',
                'unique:users,email' . ($id ? ",$id" : '')
            ],
            'password' => ($this->isMethod('post') ? 'required' : 'sometimes') . '|nullable|string|min:6',
        ];
    }
}

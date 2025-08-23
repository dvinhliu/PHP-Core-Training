<?php

namespace App\Requests;

use App\Core\Request;

class LoginRequest extends Request
{
    public function rules(): array
    {
        return [
            'user_name' => 'required|regex:/^\S*$/u',
            'password' => 'required|regex:/^\S*$/u',
        ];
    }

    public function messages(): array
    {
        return [
            'user_name.required' => 'Username không được để trống',
            'user_name.regex' => 'Username không được chứa khoảng trắng',
            'password.required' => 'Mật khẩu không được để trống',
            'password.regex' => 'Mật khẩu không được chứa khoảng trắng',
        ];
    }
}

<?php

namespace App\Requests;

use App\Core\Request;

class LoginRequest extends Request
{
    public function rules(): array
    {
        return [
            'username' => 'required|regex:/^\S*$/u',
            'password' => 'required|regex:/^\S*$/u',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username không được để trống',
            'username.regex' => 'Username không được chứa khoảng trắng',
            'password.required' => 'Mật khẩu không được để trống',
            'password.regex' => 'Mật khẩu không được chứa khoảng trắng',
        ];
    }
}

<?php

namespace App\Requests;

use App\Core\Request;
use App\Models\RoleType;

class ResetPasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'password' => 'required|min:5|max:9|regex:/^(?!.*[A-Z].*[A-Z])(?=.*[A-Z])\S+$/|confirmed',
            'password_confirmation' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải từ 5-9 ký tự',
            'password.max' => 'Mật khẩu phải từ 5-9 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'password.regex' => 'Mật khẩu phải chứa ít nhất một chữ hoa và không được chứa khoảng trắng',
            'password_confirmation.required' => 'Mật khẩu xác nhận không được để trống',
        ];
    }
}

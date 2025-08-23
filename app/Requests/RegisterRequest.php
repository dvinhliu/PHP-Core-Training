<?php

namespace App\Requests;

use App\Core\Request;
use App\Models\RoleType;

class RegisterRequest extends Request
{
    public function rules(): array
    {
        return [
            'user_name' => 'required|min:3|max:8|unique:users,user_name|regex:/^[a-zA-Z0-9]+$/',
            'email' => 'required|email|unique:users,email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'password' => 'required|min:5|max:9|confirmed|regex:/^(?!.*[A-Z].*[A-Z])(?=.*[A-Z])\S+$/',
            'password_confirmation' => 'required',
            'role_id' => 'required|in:' . implode(',', array_map(
                fn(RoleType $role) => $role->value,
                RoleType::registerRoles()
            )),
        ];
    }

    public function messages(): array
    {
        return [
            'user_name.required' => 'Username không được để trống',
            'user_name.min' => 'Username phải từ 3-8 ký tự',
            'user_name.max' => 'Username phải từ 3-8 ký tự',
            'user_name.unique' => 'Username đã tồn tại',
            'user_name.regex' => 'Username chỉ được chứa chữ cái và số',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'email.regex' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải từ 5-9 ký tự',
            'password.max' => 'Mật khẩu phải từ 5-9 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'password.regex' => 'Mật khẩu phải chứa ít nhất một chữ hoa và không được chứa khoảng trắng',
            'password_confirmation.required' => 'Mật khẩu xác nhận không được để trống',
            'role_id.required' => 'Vai trò không được để trống',
        ];
    }
}

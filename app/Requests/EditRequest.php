<?php

namespace App\Requests;

use App\Core\Request;
use App\Models\RoleType;

class EditRequest extends Request
{
    private int $id;

    public function __construct(array $data, int $id)
    {
        $this->id = $id;
        parent::__construct($data);
    }
    public function rules(): array
    {
        return [
            'user_name' => 'required|min:3|max:8|exists:users,user_name,' . $this->id . '|regex:/^[a-zA-Z0-9]+$/',
            'email' => 'required|email|exists:users,email,' . $this->id . '|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'password' => 'nullable|min:5|max:9|regex:/^(?!.*[A-Z].*[A-Z])(?=.*[A-Z])\S+$/|confirmed',
            'password_confirmation' => 'nullable',
            'role_id' => 'required|in:' . implode(',', array_map(
                fn(RoleType $role) => $role->value,
                RoleType::updateRoles()
            )),
        ];
    }

    public function messages(): array
    {
        return [
            'user_name.required' => 'Username không được để trống',
            'user_name.min' => 'Username phải từ 3-8 ký tự',
            'user_name.max' => 'Username phải từ 3-8 ký tự',
            'user_name.exists' => 'Username đã tồn tại',
            'user_name.regex' => 'Username chỉ được chứa chữ cái và số',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.exists' => 'Email đã tồn tại',
            'email.regex' => 'Email không hợp lệ',
            'password.min' => 'Mật khẩu phải từ 5-9 ký tự',
            'password.max' => 'Mật khẩu phải từ 5-9 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'password.regex' => 'Mật khẩu phải chứa ít nhất một chữ hoa và không được chứa khoảng trắng',
            'role_id.required' => 'Vai trò không được để trống',
        ];
    }
}

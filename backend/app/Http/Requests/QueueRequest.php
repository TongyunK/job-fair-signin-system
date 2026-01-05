<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QueueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|regex:/^1[3-9]\d{9}$/',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '姓名不能为空',
            'phone.required' => '手机号不能为空',
            'phone.regex' => '手机号格式不正确',
            'email.email' => '邮箱格式不正确',
        ];
    }
}


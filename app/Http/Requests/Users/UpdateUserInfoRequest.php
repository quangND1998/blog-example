<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string|unique:users,username|alpha_dash',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.required' => __('Hãy nhập tên hiển thị của bạn'),
            'username.string' => __('Tên hiển thị không đúng định dạng'),
            'username.unique' => __('Tên đã tồn tại'),
            'username.alpha_dash' => __('Tên hiển thị chỉ bao gồm số và chữ cái'),
        ];
    }
}

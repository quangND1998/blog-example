<?php

namespace App\Http\Requests\Feedbacks;

use Illuminate\Foundation\Http\FormRequest;

class CreateFeedbackRequest extends FormRequest
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
            'email' => 'required|email',
            'content' => 'required|string|min:10',
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
            'email.required' => __('Hãy nhập nội email'),
            'email.email' => __('Email không đúng định dạng'),

            'content.required' => __('Hãy nhập nội dung phản hồi'),
            'content.string' => __('Nội dung phản hồi không đúng định dạng'),
            'content.min' => __('Nội dung định dạng tối thiểu 10 kí tự'),
        ];
    }
}

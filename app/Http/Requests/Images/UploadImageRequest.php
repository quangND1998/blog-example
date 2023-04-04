<?php

namespace App\Http\Requests\Images;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
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
            'img' => 'required|file|max:2048|mimes:jpg,jpeg,png,gif,svg',
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
            'img.required' => __('Hãy chọn file ảnh'),
            'img.file' => __('Ảnh không hợp lệ'),
            'img.max' => __('Ảnh tối đa 2Mb'),
            'img.mimes' => __('Ảnh chỉ nhận định dạng jpg, jpeg, png, gif và svg'),
        ];
    }
}

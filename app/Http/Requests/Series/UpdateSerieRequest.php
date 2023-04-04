<?php

namespace App\Http\Requests\Series;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSerieRequest extends FormRequest
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
        $hashId = $this->route('series')->hash_id;

        return [
            'name' => "required|unique:series,name,$hashId,hash_id",
            'slug' => "required|unique:series,slug,$hashId,hash_id",
            'images_id' => 'exists:images,id',
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
            'name.required' => __('Hãy nhập tên serie'),
            'name.unique' => __('Tên serie đã tồn tại'),

            'slug.required' => __('Hãy nhập slug'),
            'slug.unique' => __('Slug đã tồn tại'),

            'images_id.exists' => __('Ảnh không tồn tại'),
        ];
    }
}

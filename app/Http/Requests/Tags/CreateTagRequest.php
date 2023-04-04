<?php

namespace App\Http\Requests\Tags;

use Illuminate\Foundation\Http\FormRequest;

class CreateTagRequest extends FormRequest
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
            'name' => 'required|unique:tags,name',
            'slug' => 'required|unique:tags,slug',
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
            'name.required' => __('Hãy nhập tên tag'),
            'name.unique' => __('Tên tag đã tồn tại'),

            'slug.required' => __('Hãy nhập slug'),
            'slug.unique' => __('Slug đã tồn tại'),

            'images_id.exists' => __('Ảnh không tồn tại'),
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function getValidatorInstance()
    {
        $data = $this->all();
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = $data['name'];
            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }
}

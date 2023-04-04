<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\GreaterTime;

class CreatePostRequest extends FormRequest
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
            'title' => 'required|unique:posts,title',
            'slug' => 'required|unique:posts,slug',
            'status' => 'required|in:draft,review,publish',
            'publish_at' => ['required', 'date_format:d-m-Y H:i', new GreaterTime],
            'series_id' => 'exists:series,id',
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
            'title.required' => __('Hãy nhập tên bài viết'),
            'title.unique' => __('Tên bài viết đã tồn tại'),

            'slug.required' => __('Hãy nhập slug'),
            'slug.unique' => __('Slug đã tồn tại'),

            'status.required' => __('Hãy chọn trạng thái bài viết'),
            'status.in' => __('Trạng thái bài viết không hợp lệ'),

            'publish_at.required' => __('Hãy chọn thời gian đăng bài'),
            'publish_at.date_format' => __('Thời gian đăng bài sai định dạng'),

            'series_id.exists' => __('Serie không tồn tại'),

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
        if (isset($data['title']) && !isset($data['slug'])) {
            $data['slug'] = $data['title'];
            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }
}

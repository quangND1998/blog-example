<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Post;

class GreaterOrSameTime implements Rule
{
    protected $postHashId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($postHashId)
    {
        $this->postHashId = $postHashId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $post = Post::where('hash_id', $this->postHashId)->firstOrFail();
        if (strtotime($value) === strtotime($post->publish_at)) {
            return true;
        }
        return strtotime($value) > strtotime('-2 minutes');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Thời gian đăng bài phải lớn hơn hiện tại');
    }
}

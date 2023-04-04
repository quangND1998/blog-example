<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GreaterTime implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

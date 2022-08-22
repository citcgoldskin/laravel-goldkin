<?php

namespace App\Rules\Admin;

use App\Models\QuestionClass;
use Illuminate\Contracts\Validation\Rule;

class CategoryRegisterRule implements Rule
{
    private $p_category;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($category_name)
    {
        $this->p_category = $category_name;
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
        if (QuestionClass::where('qc_name', trim($this->p_category))->where('qc_parent', 0)->exists()) {
            return false;
        }
        return  true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'すでに登録されたカテゴリーです。';
    }
}

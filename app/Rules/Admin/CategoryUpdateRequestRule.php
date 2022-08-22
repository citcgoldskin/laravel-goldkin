<?php

namespace App\Rules\Admin;

use App\Models\QuestionClass;
use Illuminate\Contracts\Validation\Rule;

class CategoryUpdateRequestRule implements Rule
{
    private $p_category;
    private $p_category_name_arr;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($category_name, $category_name_arr)
    {
        $this->p_category = $category_name;
        $this->p_category_name_arr = $category_name_arr;
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
        $counts = array_count_values($this->p_category_name_arr);
        $duplicate_count =  $counts[$value];
        if ($duplicate_count > 1) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'カテゴリー名が重複しています。';
    }
}

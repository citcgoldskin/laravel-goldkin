<?php

namespace App\Rules\Admin;

use App\Models\QuestionClass;
use Illuminate\Contracts\Validation\Rule;

class CategoryUpdateRule implements Rule
{
    private $p_category;
    private $p_category_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($category_name, $category_id)
    {
        $this->p_category = $category_name;
        $this->p_category_id = $category_id;
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
        if (QuestionClass::where('qc_name', trim($value))->where('qc_id', '!=', $this->p_category_id)->where('qc_parent', 0)->exists()) {
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
        return 'すでに登録されたカテゴリーです。';
    }
}

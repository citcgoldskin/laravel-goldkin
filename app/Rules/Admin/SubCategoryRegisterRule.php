<?php

namespace App\Rules\Admin;

use App\Models\QuestionClass;
use Illuminate\Contracts\Validation\Rule;

class SubCategoryRegisterRule implements Rule
{
    private $p_category_id;
    private $p_sub_category;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($category_id, $sub_category_name)
    {
        $this->p_category_id = $category_id;
        $this->p_sub_category = $sub_category_name;
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
        if (QuestionClass::where('qc_name', trim($this->p_sub_category))->where('qc_parent', $this->p_category_id)->exists()) {
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
        return 'すでに登録されたサブカテゴリーです。';
    }
}

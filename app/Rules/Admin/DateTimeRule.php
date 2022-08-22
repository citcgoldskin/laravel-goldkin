<?php

namespace App\Rules\Admin;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class DateTimeRule implements Rule
{
    private $p_request;
    private $p_prefix;
    private $p_title;
    private $p_message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Request $request, $prefix,  $title='')
    {
        $this->p_request = $request;
        $this->p_prefix = $prefix;
        $this->p_title = $title;
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
        $idx_year = $this->p_prefix . '_year';
        $idx_month = $this->p_prefix . '_month';
        $idx_day = $this->p_prefix . '_day';
        $idx_hour = $this->p_prefix . '_hour';
        $idx_minute = $this->p_prefix . '_minute';
        if($this->p_request->input($idx_year) && $this->p_request->input($idx_month) && $this->p_request->input($idx_day) && $this->p_request->input($idx_hour) && $this->p_request->input($idx_minute)) {
            return true;
        } else {
            $this->p_message = $this->p_title . '年月日時を正確に選択してください。';
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->p_message;
    }
}

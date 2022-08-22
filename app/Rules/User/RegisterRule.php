<?php

namespace App\Rules\User;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class RegisterRule implements Rule
{
    private $email;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
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
        if (User::where('email', $this->email)->whereNotNull('email_verified_at')->exists()) {
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
        return 'すでに登録されたメールアドレスです。';
    }
}

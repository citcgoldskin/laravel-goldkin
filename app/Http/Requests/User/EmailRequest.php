<?php

namespace App\Http\Requests\User;

use App\Rules\User\RegisterRule;
use Illuminate\Foundation\Http\FormRequest;

class EmailRequest extends FormRequest
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
        $rules['email'] = ['required', 'string', 'email', new RegisterRule($this->email)];
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['email']  = "メールアドレス";
        return $attributes;
    }

    public function messages()
    {
        $message = array();

        return $message;
    }
}

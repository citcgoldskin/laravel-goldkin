<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        if ( $this->input('tab_item') == config('const.login_mode.email') ) {
            $rules['email'] = ['required', 'string', 'email'];
        } else {
            $rules['user_phone'] = ['required', 'int', 'max:99999999999'];
        }

        $rules['password'] = ['required'];

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        if ( $this->input('tab_item') == config('const.login_mode.email') ) {
            $attributes['email']  = "メールアドレス";
        } else {
            $attributes['user_phone']  = "電話番号";
        }

        $attributes['password']  = "パスワード";
        return $attributes;
    }

    public function messages()
    {
        $message = array();
        return $message;
    }
}

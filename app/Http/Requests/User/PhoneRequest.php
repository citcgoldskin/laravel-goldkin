<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PhoneRequest extends FormRequest
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
        $rules['phone'] = ['required', 'numeric', 'max:99999999999', 'unique:users,user_phone'];
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['phone']  = "電話番号";
        return $attributes;
    }

    public function messages()
    {
        $message = array();
        return $message;
    }
}

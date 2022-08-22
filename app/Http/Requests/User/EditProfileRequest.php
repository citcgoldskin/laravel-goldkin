<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class EditProfileRequest extends FormRequest
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
        $rules['name'] = ['required', 'string', 'max:50'];
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['name']  = "ニックネーム";
        return $attributes;
    }

    public function messages()
    {
        $message = array();

        return $message;
    }
}

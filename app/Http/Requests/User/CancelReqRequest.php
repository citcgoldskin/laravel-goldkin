<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CancelReqRequest extends FormRequest
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
        $rules['cancel_list'] = ['required'];

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        return $attributes;
    }

    public function messages()
    {
        $message['cancel_list.required'] = "キャンセルするリクエストを選択してください。";

        return $message;
    }
}

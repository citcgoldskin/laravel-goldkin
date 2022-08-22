<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class RegisterProfileRequest extends FormRequest
{
    protected $_request;

    public function __construct(Request $request, Factory $factory)
    {
        $this->_request = $request;
    }
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
        $params = $this->_request->all();
        $rules['name'] = ['required', 'string', 'max:50'];
        $rules['email'] = ['required', 'string', 'email'];
        $rules['password'] = ['required', 'string', 'min:7'];
        $rules['user_firstname'] = ['required', 'string', 'max:50'];
        $rules['user_lastname'] = ['required', 'string', 'max:50'];
        $rules['user_sei'] = ['required', 'string', 'max:50'];
        $rules['user_mei'] = ['required', 'string', 'max:50'];
        $rules['user_birthday'] = ['required', 'date'];
        $rules['user_sex'] = ['required', 'numeric', 'min:1'];
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['name'] = "ニックネーム";
        $attributes['email'] = "メールアドレス";
        $attributes['password'] = "パスワード";
        $attributes['user_firstname'] = "姓";
        $attributes['user_lastname'] = "名";
        $attributes['user_sei'] = "セイ";
        $attributes['user_mei'] = "メイ";
        $attributes['user_birthday'] = "生年月日";
        $attributes['user_sex'] = "性別";
        return $attributes;
    }

    public function messages()
    {
        return [
            'user_sex.required' => '性別を選択してください。',
            'user_sex.min' => '性別を選択してください。',
        ];
    }
}

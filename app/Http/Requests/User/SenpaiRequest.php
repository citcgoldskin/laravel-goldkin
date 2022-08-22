<?php

namespace App\Http\Requests\User;

use App\Rules\User\BirthdayRule;
use Illuminate\Foundation\Http\FormRequest;

class SenpaiRequest extends FormRequest
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
        $rules['senpai_mail'] = ['required', 'string'];
        $rules['senpai_area_id'] = ['required'];
        $rules['senpai_county'] = ['required', 'min:1',  'max:50'];
        $rules['senpai_village'] = ['required','min:1',  'max:50'];
        $rules['senpai_mansyonn'] = ['required', 'min:1',  'max:50'];
        $rules['user_firstname'] = ['required', 'min:1',  'max:50'];
        $rules['user_lastname'] = ['required', 'min:1',  'max:50'];
        $rules['user_sei'] = ['required', 'min:1',  'max:50'];
        $rules['user_mei'] = ['required', 'min:1',  'max:50'];
        $rules['birthday_year'] = ['required', 'integer'];
        $rules['birthday_month'] = ['required', 'integer'];
        $rules['birthday_day'] = ['required', 'integer'];
        $rules['sex'] = ['integer', 'min:1'];
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['senpai_mail'] ="郵便番号";
        $attributes['senpai_area_id'] = "都道府県";
        $attributes['senpai_county'] ="市区町村";
        $attributes['senpai_village'] = "町番地";
        $attributes['senpai_mansyonn'] = "マンション名・部屋番号";
        $attributes['user_firstname'] ="姓";
        $attributes['user_lastname'] = "名";
        $attributes['user_sei'] = "姓";
        $attributes['user_mei'] = "名";
        $attributes['birthday_year'] = "年";
        $attributes['birthday_month'] = "月";
        $attributes['birthday_day'] = "日";
        $attributes['sex'] = "性別";
        return $attributes;
    }

    public function messages()
    {
        return [
            'sex.min' => '性別を正確に入力してください。',
        ];
    }
}

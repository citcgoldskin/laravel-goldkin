<?php

namespace App\Http\Requests\User;

use App\Rules\User\BirthdayRule;
use Illuminate\Foundation\Http\FormRequest;

class CardCreditRequest extends FormRequest
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
        $rules['company_id'] = ['integer', 'min:1'];
        $rules['number'] = ['required', 'string', 'max:255'];
        $rules['valid_month'] = ['integer', 'min:1'];
        $rules['valid_year'] = ['integer', 'min:1'];
        $rules['client_name'] = ['required', 'string', 'max:255'];
        $rules['security_code'] = ['required', 'string', 'max:255'];
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['company_id']  = "カード会社";
        $attributes['number']  = "カード番号";
        $attributes['valid_month']  = "月";
        $attributes['valid_year']  = "年";
        $attributes['client_name']  = "名義人";
        $attributes['security_code']  = "セキュリティーコード";
        return $attributes;
    }

    public function messages()
    {
        return [
            'company_id.min' => 'カード会社を入力してください。',
            'number.required' => 'カード番号を入力してください。',
            'number.max' => 'カード番号を255文字以下で入力してください。',
            'valid_month.min' => '月を入力してください。',
            'valid_year.min' => '年を入力してください。',
            'client_name.required' => '名義人を入力してください。',
            'client_name.max' => '名義人を255文字以下で入力してください。',
            'security_code.required' => 'セキュリティーコードを入力してください。',
            'security_code.max' => 'セキュリティーコードを255文字以下で入力してください。'
        ];
    }
}

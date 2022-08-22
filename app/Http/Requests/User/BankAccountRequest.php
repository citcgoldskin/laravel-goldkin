<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class BankAccountRequest extends FormRequest
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

        $rules['bank_id'] = ['required', 'numeric', 'min:1'];
        $rules['bank_account_type'] = ['required', 'numeric', 'min:1'];
        $rules['bank_branch'] = ['required', 'string', 'max:50'];
        $rules['bank_account_no'] = ['required', 'string', 'max:50'];
        $rules['bank_account_name'] = ['required', 'string', 'max:50'];

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();

        $attributes['bank_id']  = "金融機関名";
        $attributes['bank_account_type']  = "口座種別";
        $attributes['bank_branch']  = "支店コード";
        $attributes['bank_account_no']  = "口座番号";
        $attributes['bank_account_name']  = "口座名義（カナ）";

        return $attributes;
    }

    public function messages()
    {
        return [
            'bank_id.required' => '金融機関名を選択してください。',
            'bank_account_type.required' => '口座種別を選択してください。',
            'bank_branch.required' => '支店コードを入力してください。',
            'bank_account_no.required' => '口座番号を入力してください。',
            'bank_account_name.required' => '口座名義（カナ）を入力してください。',
        ];
    }
}

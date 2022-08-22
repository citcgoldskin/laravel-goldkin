<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class TransferApplicationRequest extends FormRequest
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
        $rules['transfer_all_price'] = ['required', 'numeric', 'gt:transfer_fee'];

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();

        $attributes['transfer_all_price']  = "振込申請金額";

        return $attributes;
    }

    public function messages()
    {
        return [
            'bank_id.required' => '振込申請金額を入力してください。',
            'bank_id.gt' => '振込申請金額を振込手数料より大きく入力してください。',
        ];
    }
}

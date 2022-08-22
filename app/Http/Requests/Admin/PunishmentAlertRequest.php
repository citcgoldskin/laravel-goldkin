<?php

namespace App\Http\Requests\Admin;

use App\Rules\User\BirthdayRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class PunishmentAlertRequest extends FormRequest
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

        $rules['alert_title'] = ['required', 'string', 'max:256'];
        $rules['alert_text'] = ['required', 'string'];
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['alert_title']  = "表題";
        $attributes['alert_text'] = "通知文";
        return $attributes;
    }

    public function messages()
    {
        return [
            'alert_title.required' => '表題を入力してください。',
            'alert_text.required' => '通知文を入力してください。'
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Rules\Admin\DateTimeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class MaintenanceRequest extends FormRequest
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
        $rules['start_year'] = ['required', new DateTimeRule($this->_request,'start', '開始')];
        $rules['end_year'] = ['required', new DateTimeRule($this->_request, 'end', '終了')];
        $rules['notice_year'] = ['required', new DateTimeRule($this->_request,'notice', '通知日時')];
        $rules['services'] = ['required', 'array'];

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['start_year']  = "開始";
        $attributes['end_year']  = "終了";
        $attributes['notice_year']  = "対象サービス";
        $attributes['services']  = "対象サービス";
        return $attributes;
    }

    public function messages()
    {
        return [
            'start_year.required' => '開始年月日時を正確に選択してください。',
            'end_year.required' => '終了月日時を正確に選択してください。',
            'notice_year.required' => '通知年月日時を正確に選択してください。',
            'services.required' => '対象サービスを選択してください。',
        ];
    }
}

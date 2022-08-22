<?php

namespace App\Http\Requests\Admin;

use App\Rules\Admin\DateTimeRule;
use App\Rules\Admin\MaintenanceRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class NewsPublishRequest extends FormRequest
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
        $rules['status'] = ['required'];
        if($this->_request->input('status') == config('const.news_status_code.limit_publish')) {
            $rules['publish_year'] = ['required', new DateTimeRule($this->_request,'publish')];
        }

        if($this->_request->input('status') == config('const.news_status_code.limit_n_publish')) {
            $rules['n_publish_year'] = ['required', new DateTimeRule($this->_request,'n_publish')];
        }
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['status']  = "公開設定";
        return $attributes;
    }

    public function messages()
    {
        return [
            'status.required' => '公開設定を選択してください。',
        ];
    }
}

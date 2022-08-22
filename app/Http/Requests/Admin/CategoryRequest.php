<?php

namespace App\Http\Requests\Admin;

use App\Rules\Admin\CategoryRegisterRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class CategoryRequest extends FormRequest
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

        $rules['category_name'] = ['required', 'string', new CategoryRegisterRule($params['category_name'])];

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['category_name']  = "カテゴリー名";
        return $attributes;
    }

    public function messages()
    {
        return [
            'category_name.required' => 'カテゴリー名を入力してください。',
        ];
    }
}

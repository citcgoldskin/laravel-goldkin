<?php

namespace App\Http\Requests\Admin;

use App\Rules\Admin\MaintenanceRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class NewsRequest extends FormRequest
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
        $rules['category'] = ['required'];
        $rules['title'] = ['required', 'string', 'max:1000'];
        $rules['content'] = ['required', 'string', 'max:5000'];
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['category']  = "カテゴリー";
        $attributes['title']  = "表題";
        $attributes['content']  = "本文";
        return $attributes;
    }

    public function messages()
    {
        return [
            'category.required' => 'カテゴリーを入力してください。',
            'title.required' => '表題を入力してください。',
            'content.required' => '本文を入力してください。',
        ];
    }
}

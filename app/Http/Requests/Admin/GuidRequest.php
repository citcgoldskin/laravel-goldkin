<?php

namespace App\Http\Requests\Admin;

use App\Rules\Admin\CategoryRegisterRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class GuidRequest extends FormRequest
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
        $rules['description'] = ['required', 'string', 'max:10000'];
        $rules['file_path'] = ['required', 'string', 'max:256'];
        $rules['link'] = ['required', 'url'];

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['description']  = "説明";
        $attributes['file_path']  = "画像";
        $attributes['link']  = "画像クリック後の参照先";
        return $attributes;
    }

    public function messages()
    {
        return [
            'description.required' => '説明を入力してください。',
            'file_path.required' => '画像を選択してください。',
            'link.required' => 'クリック名を入力してください。',
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Rules\Admin\CategoryRegisterRule;
use App\Rules\Admin\CategoryUpdateRequestRule;
use App\Rules\Admin\CategoryUpdateRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class CategoryUpdateRequest extends FormRequest
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
        $categories = $this->_request->input('qc_name');
        $has_null = in_array(null, $categories, true);
        foreach ($categories as $key=>$value) {
            // $rules['qc_name.'.$key] = ['required', 'string', new CategoryUpdateRequestRule($value, $categories), new CategoryUpdateRule($value, $key)];
            if ($has_null) {
                $rules['qc_name.'.$key] = ['required', 'string'];
            } else {
                $rules['qc_name.'.$key] = ['required', 'string', new CategoryUpdateRequestRule($value, $categories)];
            }
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();

        $categories = $this->_request->input('qc_name');
        foreach ($categories as $key=>$value) {
            $attributes['qc_name.'.$key] = "カテゴリー名";
        }
        return $attributes;
    }

    public function messages()
    {
        $ret = [];
        $categories = $this->_request->input('qc_name');
        foreach ($categories as $key=>$value) {
            $attributes['qc_name.'.$key] = "カテゴリー名";
            $ret['qc_name.'.$key.'.required'] = 'カテゴリー名を入力してください。';
        }
        return $ret;
    }
}

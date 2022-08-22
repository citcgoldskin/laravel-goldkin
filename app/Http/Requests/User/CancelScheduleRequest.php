<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CancelScheduleRequest extends FormRequest
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
        $rules['commitment'] = ['required'];

        $reasons = $this->input('commitment');
        if ( is_array($reasons) && (in_array(config('const.kouhai_cancel_other_reason_id'), $reasons) || in_array(config('const.senpai_cancel_other_reason_id'), $reasons)) ) {
            $rules['other_reason'] = ['required'];
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['commitment']  = "キャンセルの理由";
        $attributes['other_reason'] = "その他";
        return $attributes;
    }

    public function messages()
    {
        $message['commitment.required'] = "キャンセルの理由を選択してください.";

        return $message;
    }
}

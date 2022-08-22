<?php

namespace App\Http\Requests\Admin;

use App\Rules\User\BirthdayRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class PunishmentRequest extends FormRequest
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

        $rules['decision_type'] = ['required', 'integer', 'min:1'];
        $rules['stop_period'] = ['nullable', 'integer', 'min:1'];
        $rules['basis'] = ['required', 'array'];
        $rules['reason'] = ['required', 'array'];

        if (isset($params['decision_type']) && ($params['decision_type'] == config('const.punishment_decision_code.lesson_article_stop') || $params['decision_type'] == config('const.punishment_decision_code.buy_sell_stop'))) {
            $rules['stop_period'] = ['required', 'integer', 'min:1'];
        }
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['decision_type']  = "決定";
        $attributes['basis'] = "根拠通報";
        $attributes['reason'] = "理由";
        $attributes['stop_period'] = "停止期間";
        return $attributes;
    }

    public function messages()
    {
        return [
            'decision_type.required' => '決定を選択してください。',
            'stop_period.required' => '停止期間を選択してください。',
            'reason.required' => '理由を選択してください。',
            'basis.required' => '根拠通報を選択してください。',
        ];
    }
}

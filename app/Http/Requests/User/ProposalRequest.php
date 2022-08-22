<?php

namespace App\Http\Requests\User;

use App\Rules\User\BirthdayRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class ProposalRequest extends FormRequest
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
        $current_datetime = Carbon::now()->format('Y-m-d H:i:s');

        $rules['prop_money'] = ['required', 'integer', 'min:1'];
        /*$rules['traffic_fee'] = ['integer', 'min:1'];*/
        /*$rules['message'] = ['required', 'string', 'max:1000'];*/
        $rules['start_hour'] = ['required', 'numeric'];
        $rules['start_minute'] = ['required', 'numeric'];
        $rules['end_hour'] = ['required','numeric', 'gte:start_hour'];
        $rules['end_minute'] = ['required','numeric'];
        $rules['period_start'] = ['required','numeric'];
        /*$rules['period_end'] = ['required','numeric', 'gte:period_start'];*/
        $rules['rc_period'] = ['required', 'date_format:Y-m-d H:i:s'];
        //$rules['purchase_period'] = ['required','date_format:Y-m-d H:i:s', 'after:'.$current_datetime, 'before:rc_period'];
        $rules['purchase_period'] = ['required', 'date_format:Y-m-d H:i:s', 'before:rc_period', 'after_or_equal:'.$current_datetime];

        if($params['start_hour'] == $params['end_hour']) {
            $rules['end_minute'] = ['required','numeric', 'gte:start_minute'];
        }
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['prop_money']  = "";
        $attributes['message']  = "";
        $attributes['start_hour']  = "レッスン開始日時";
        $attributes['end_hour']  = "レッスン開始日時";
        $attributes['start_minute']  = "レッスン開始日時";
        $attributes['end_minute']  = "レッスン開始日時";
        $attributes['period_start']  = "レッスン時間";
        $attributes['period_end']  = "レッスン時間";
        $attributes['purchase_period']  = "購入期限";
        $attributes['rc_period']  = "募集期限";
        return $attributes;
    }

    public function messages()
    {
        return [
            'prop_money.required' => '提案金額を入力してください。',
            'end_hour.gte' => 'レッスン開始日時を正確に入力してください。',
            'period_end.gte' => 'レッスン時間を正確に入力してください。',
            'end_minute.gte' => 'レッスン開始日時を正確に入力してください。',
            'prop_money.min' => '提案金額を正確に入力してください。',
            'prop_money.integer' => '提案金額を正確に入力してください。',
            'traffic_fee.min' => '出張交通費を正確に入力してください。',
            'traffic_fee.integer' => '出張交通費を正確に入力してください。',
            'message.required' => 'メッセージを入力してください。',
            'purchase_period.after_or_equal' => '購入期限には、現在以降もしくは同日時を指定してください。',
            'message.max' => 'メッセージを1000文字以下で入力してください。'
        ];
    }
}

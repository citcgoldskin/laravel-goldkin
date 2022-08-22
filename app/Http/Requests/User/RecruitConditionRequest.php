<?php

namespace App\Http\Requests\User;

use App\Rules\User\BirthdayRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class RecruitConditionRequest extends FormRequest
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
        $current_date = Carbon::now()->format('Y-m-d');

        //$rules['area_id_2'] = ['required', 'string'];
        $rules['province_id'] = ['required','numeric'];
        $rules['date'] = ['required','date'];
        $rules['recruit_date'] = ['required','date', 'before_or_equal:date', 'after_or_equal:today'];
        $rules['start_hour'] = ['required','numeric'];
        $rules['start_minute'] = ['required','numeric'];
        /*$rules['start_interval'] = ['required','numeric'];*/
        /*$rules['start_fee'] = ['required','numeric'];*/
        $rules['period_hour'] = ['required','numeric'];

        if (isset($params['date']) && Carbon::now()->format('Y-m-d') == $params['date']) {
            $current_min = Carbon::now()->format('m');
            $current_hour = Carbon::now()->format('H') + 3;
            if ($current_min == 0) {
                $current_hour = Carbon::now()->format('H') + 2;
            }
            $rules['start_hour'] = ['required','numeric', 'gte:'.$current_hour];
        }

        if (isset($params['recruit_date'])) {
            $current_min = Carbon::now()->format('m');
            $current_hour = Carbon::now()->format('H') + 1;
            if ($current_min == 0) {
                $current_hour = Carbon::now()->format('H');
            }
            if ($params['recruit_date'] == $current_date) {
                $rules['period_hour'] = ['required','numeric', 'gt:'.$current_hour];
            }

            if (isset($params['date'])) {
                if ($params['recruit_date'] == $params['date']) {
                    if ($params['recruit_date'] == $current_date) {
                        $rules['period_hour'] = ['required','numeric', 'lt:start_hour', 'gt:'.$current_hour];
                    } else {
                        $rules['period_hour'] = ['required','numeric', 'lt:start_hour'];
                    }
                }
            }
        }

        if (!(isset($params['start_interval']) && $params['start_interval']) && !(isset($params['end_interval']) && $params['end_interval'])) {
            $rules['start_interval'] = ['required','numeric'];
        }

        if (!(isset($params['start_fee']) && $params['start_fee']) && !(isset($params['end_fee']) && $params['end_fee'])) {
            $rules['start_fee'] = ['required','numeric'];
        }

        if (isset($params['start_hour']) && isset($params['end_hour'])) {
            if ($params['start_hour'] > $params['end_hour']) {
                $rules['end_hour'] = ['required','numeric', 'gte:start_hour'];
            } else if($params['start_hour'] == $params['end_hour']) {
                $rules['end_minute'] = ['required','numeric', 'gt:start_minute'];
            }
        }

        if (isset($params['start_interval']) && isset($params['end_interval'])) {
            if ($params['start_interval'] > $params['end_interval']) {
                $rules['end_interval'] = ['required','numeric', 'gt:start_interval'];
            }
        }

        if (isset($params['start_fee']) && isset($params['end_fee'])) {
            if ($params['start_fee'] > $params['end_fee']) {
                $rules['end_fee'] = ['required','numeric', 'gt:start_fee'];
            }
        }

        if (isset($params['start_age']) && isset($params['end_age'])) {
            if ($params['start_age'] > $params['end_age']) {
                $rules['end_age'] = ['nullable','numeric', 'gt:start_age'];
            }
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['area_id_2']  = "エリア";
        $attributes['province_id']  = "都道府県";
        $attributes['lesson_time']  = "レッスン時間";
        $attributes['money']  = "料金";
        $attributes['period']  = "募集期限";
        $attributes['date']  = "レッスン日";
        $attributes['recruit_date']  = "募集期限";
        $attributes['start_hour']  = "レッスン開始時間";
        $attributes['start_minute']  = "レッスン開始時間";
        $attributes['end_hour']  = "レッスン締め切り時間";
        $attributes['end_minute']  = "レッスン締め切り時間";
        $attributes['start_interval']  = "レッスン時間";
        $attributes['end_interval']  = "レッスン時間";
        $attributes['start_fee']  = "料金下限";
        $attributes['end_fee']  = "料金上限";
        $attributes['start_age']  = "年代";
        $attributes['end_age']  = "年代";
        $attributes['period_hour']  = "募集期限（時）";
        return $attributes;
    }

    public function messages()
    {
        return [
            'area_id_2.required' => 'エリアを選択してください。',
            'province_id.required' => '都道府県を選択してください。',
            //'area_id_2.min' => 'エリアを選択してください。',
            'lesson_time.required' => 'レッスン時間を入力してください。',
            'lesson_time.integer' => 'レッスン時間を正確に入力してください。',
            'lesson_time.min' => 'レッスン時間を正確に入力してください。',
            'money.required' => '料金を入力してください。',
            'money.min' => '料金を正確に入力してください。',
            'money.integer' => '料金を正確に入力してください。',
            'period.required' => '募集期限を入力してください。',
            'period.date' => '募集期限を正確に入力してください。',
            'date.required' => 'レッスン日を入力してください。',
            'recruit_date.before_or_equal' => '現時点より1時間後からレッスン開始日時の最も早い場合の1時間前まで設定可能です。',
            'recruit_date.after_or_equal' => '現時点より1時間後からレッスン開始日時の最も早い場合の1時間前まで設定可能です。',
            'period_hour.lt' => '現時点より1時間後からレッスン開始日時の最も早い場合の1時間前まで設定可能です。',
            'period_hour.gt' => '現時点より1時間後からレッスン開始日時の最も早い場合の1時間前まで設定可能です。',
            'start_interval.required' => 'レッスン時間を入力してください。',
            'start_hour.gte' => 'レッスン時間を現時刻より2時間後以降に入力してください。',
            'end_hour.gt' => 'レッスン日時を正確に入力してください。',
            'end_minute.gt' => 'レッスン日時を正確に入力してください。',
            'start_fee.required' => '料金下限を入力してください。',
            'end_fee.gt' => '料金を正確に入力してください。',
            'end_age.gt' => '年代を正確に入力してください。',
            'end_interval.gt' => 'レッスン時間を正確に入力してください。',
            'recruit_date.required' => '募集期限を選択してください。',
            'period_hour.required' => '募集期限（時）を選択してください。',
        ];
    }
}

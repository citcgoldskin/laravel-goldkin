<?php

namespace App\Http\Requests\User;

use App\Rules\User\BirthdayRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class RecruitRequest extends FormRequest
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

        $rules = array();
        if ($params['state'] == config('const.recruit_state.draft') || $params['mode'] == 'delete') {
            return $rules;
        }
        $rules['lesson_classes'] = ['required', 'numeric', 'min:1'];
        $rules['title'] = ['required', 'string', 'max:50'];
        $rules['date'] = ['required', 'date', 'after_or_equal:today'];
        $rules['start_hour'] = ['required', 'numeric'];
        $rules['start_minute'] = ['required', 'numeric'];
        $rules['end_hour'] = ['required', 'numeric'];
        $rules['end_minute'] = ['required', 'numeric'];
        $rules['period_start'] = ['required', 'numeric'];
        //$rules['count_man'] = ['numeric'];
        //$rules['count_woman'] = ['numeric'];
        /*$rules['money_start'] = ['required', 'numeric'];*/
        /*$rules['money_end'] = ['required', 'numeric'];*/
        //$rules['place'] = ['required', 'string', 'max:100'];
        //$rules['place_detail'] = ['required', 'string', 'max:200'];
        $rules['recruit_detail'] = ['required', 'string', 'max:1000'];
        $rules['recruit_date'] = ['required','date', 'before_or_equal:date', 'after_or_equal:today'];
        $rules['tags'] = ['required', 'array'];
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

        /*if (isset($params['recruit_date']) && isset($params['date'])) {
            $rules['recruit_date'] = ['required','date', 'before_or_equal:date'];
            if ($params['recruit_date'] == $params['date']) {
                $current_hour = Carbon::now()->format('H');
                $rules['period_hour'] = ['required','numeric', 'lt:start_hour', 'gt:'.$current_hour];
            }
        }*/

        if ((isset($params['count_man']) && $params['count_man'] == 0) && (isset($params['count_woman']) && $params['count_woman'] == 0)) {
            $rules['count_man'] = ['required', 'numeric', 'gt:0'];
        }

        if (!(isset($params['money_start']) && $params['money_start']) && !(isset($params['money_end']) && $params['money_end'])) {
            $rules['money_start'] = ['required','numeric'];
        }

        if (isset($params['start_hour']) && isset($params['end_hour'])) {
            if ($params['start_hour'] > $params['end_hour']) {
                $rules['end_hour'] = ['required','numeric', 'gte:start_hour'];
            } else if($params['start_hour'] == $params['end_hour']) {
                $rules['end_minute'] = ['required','numeric', 'gt:start_minute'];
            }
        }

        if (isset($params['money_start']) && isset($params['money_end'])) {
            if ($params['money_start'] > $params['money_end']) {
                $rules['money_end'] = ['required','numeric', 'gt:money_start'];
            }
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['lesson_classes']  = "カテゴリー";
        $attributes['title']  = "募集タイトル";
        $attributes['date']  = "レッスン開始日時";
        $attributes['start_hour']  = "レッスン開始日時";
        /*$attributes['period_hour']  = "募集期限日時";*/
        $attributes['start_minute']  = "レッスン開始日時";
        $attributes['end_hour']  = "レッスン開始日時";
        $attributes['end_minute']  = "レッスン開始日時";
        $attributes['period_start']  = "レッスン時間";
        $attributes['period_end']  = "レッスン時間";
        $attributes['count_man']  = "参加人数";
        $attributes['count_woman']  = "参加人数";
        $attributes['money_start']  = "希望金額";
        $attributes['money_end']  = "希望金額";
        $attributes['place']  = "レッスン場所";
        $attributes['place_detail']  = "待ち合わせ場所の詳細";
        $attributes['recruit_detail']  = "募集詳細";
        $attributes['recruit_date']  = "募集期限";
        $attributes['period_hour']  = "募集期限（時）";
        $attributes['tags']  = "レッスン場所";
        return $attributes;
    }

    public function messages()
    {
        return [
            'lesson_classes.required' => 'カテゴリーを選択してください。',
            'tags.required' => 'レッスン場所を選択してください。',
            'lesson_classes.min' => 'カテゴリーを選択してください。',
            'title.required' => '募集タイトルを入力してください。',
            'date.required' => 'レッスン開始日時を選択してください。',
            'date.after_or_equal' => 'レッスン開始日時を現時刻より2時間後以降に選択してください。',
            'start_hour.required' => '',
            'start_hour.gte' => 'レッスン時間を現時刻より2時間後以降に入力してください。',
            'start_minute.required' => '',
            'end_hour.required' => '',
            'end_minute.required' => '',
            'end_hour.gte' => 'レッスン開始日時を正確に入力してください。',
            'end_minute.gt' => 'レッスン開始日時を正確に入力してください。',
            'count_man.gt' => '参加人数を入力してください。',
            'period_start.required' => 'レッスン時間を選択してください。',
            'period_end.gt' => 'レッスン時間を正確に入力してください。',
            'money_start.required' => '希望金額下限を入力してください。',
            'money_end.required' => '希望金額上限を入力してください。',
            'money_end.gt' => '希望金額を正確に入力してください。',
            'place.required' => 'レッスン場所を入力してください。',
            'place_detail.required' => '待ち合わせ場所の詳細を正確に入力してください。',
            'recruit_detail.required' => '募集詳細を入力してください。',
            'recruit_date.required' => '募集期限を選択してください。',
            'recruit_date.before_or_equal' => '現時点より1時間後からレッスン開始日時の最も早い場合の1時間前まで設定可能です。',
            'recruit_date.after_or_equal' => '現時点より1時間後からレッスン開始日時の最も早い場合の1時間前まで設定可能です。',
            'period_hour.required' => '募集期限（時）を選択してください。',
            'period_hour.lt' => '現時点より1時間後からレッスン開始日時の最も早い場合の1時間前まで設定可能です。',
            'period_hour.gt' => '現時点より1時間後からレッスン開始日時の最も早い場合の1時間前まで設定可能です。',
        ];
    }
}

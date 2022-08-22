<?php

namespace App\Http\Requests\User;

use App\Rules\User\BirthdayRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class LessonReserveRequest extends FormRequest
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
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $params = $this->_request->all();

        $invalid_date = 0;
        $min_date = "";
        foreach ($params as $key=>$val) {
            if (strpos($key, "reserve_ok_") !== false && strpos($key, "reserve_ok_") >= 0) {
                $str_date = explode("~", $val)[0];
                $date_and_time = explode(" (", $str_date);
                $date_only = $date_and_time[0];
                $split_data = explode("年", $date_only);
                $year = $split_data[0];
                $split_data = explode("月", $split_data[1]);
                $month = $split_data[0];
                $split_data = explode("日", $split_data[1]);
                $day = $split_data[0];
                $time_only = $date_and_time[1];
                $split_data = explode(")  ", $time_only);
                $time = $split_data[1];

                // $check_date = Carbon::parse($year.'-'.$month.'-'.$day.' '.$time)->format('Y-m-d H:i:s');
                $check_date = Carbon::parse($year.'-'.$month.'-'.$day)->format('Y-m-d');
                if ($min_date == "") {
                    $min_date = $check_date;
                } else if($check_date < $min_date) {
                    $min_date = $check_date;
                }
                $now_date = Carbon::now()->addDays(1)->format('Y-m-d');
                if ($now_date > $check_date) {
                    $invalid_date = 1;
                }

            }
        }

        if ($invalid_date) {
            $rules['invalid_date'] = ['required', 'numeric'];
        }

        $rules['reserve_flag'] = ['required', 'integer', 'min:1'];
        $rules['member_flag'] = ['required', 'integer', 'min:1'];

        if (isset($params['pos_discuss']) && $params['pos_discuss'] == 1) {
            $rules['area_id'] = ['required', 'integer'];
            $rules['address'] = ['required', 'string'];
            $rules['address_detail'] = ['required', 'string', 'max:200'];
        }
        if (isset($params['until_confirm'])) {
            if ($min_date != "" && $invalid_date != 1) {
                $rules['until_confirm'] = ['required', 'date', 'after_or_equal:today', 'before:'.Carbon::parse($min_date)->format('Y-m-d')];
            } else {
                $rules['until_confirm'] = ['required', 'date', 'after_or_equal:today'];
            }
        }
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        return $attributes;
    }

    public function messages()
    {
        return [
            'invalid_date.required' => 'スケジュールは明日から設定可能です。',
            'reserve_flag.required' => 'スケジュールを正確に選択してください。',
            'until_confirm.after_or_equal' => '現時点後から設定可能です。',
            'until_confirm.before' => 'レッスン開始日前まで設定可能です。',
            'reserve_flag.min' => 'スケジュールを正確に選択してください。',
            'member_flag.required' => '参加人数を選択してください。',
            'area_id.required' => 'エリアを選択してください。',
            'address.required' => '続きの住所を入力してください。',
            'address_detail.required' => '待ち合わせ場所の詳細を入力してください。',
            'member_flag.min' => '参加人数を選択してください。'
        ];
    }
}

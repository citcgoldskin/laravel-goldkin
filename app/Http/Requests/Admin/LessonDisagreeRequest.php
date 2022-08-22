<?php

namespace App\Http\Requests\Admin;

use App\Rules\Admin\CategoryRegisterRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class LessonDisagreeRequest extends FormRequest
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
        $reason_exist = false;
        if (isset($params['lesson_image']) && !is_null($params['lesson_image'])) { // レッスンイメージ
            $rules['reason_lesson_image'] = ['required', 'string', 'max:500'];
            $rules['lesson_image_item'] = ['required', 'array'];
            $reason_exist = true;
        }
        if (isset($params['lesson_title']) && !is_null($params['lesson_title'])) { // レッスンタイトル
            $rules['reason_lesson_title'] = ['required', 'string', 'max:500'];
            $reason_exist = true;
        }
        if (isset($params['lesson_service_details']) && !is_null($params['lesson_service_details'])) { // サービス詳細
            $rules['reason_lesson_service_details'] = ['required', 'string', 'max:500'];
            $reason_exist = true;
        }
        if (isset($params['lesson_other_details']) && !is_null($params['lesson_other_details'])) { // 持ち物・その他の費用
            $rules['reason_lesson_other_details'] = ['required', 'string', 'max:500'];
            $reason_exist = true;
        }
        if (isset($params['lesson_buy_and_attentions']) && !is_null($params['lesson_buy_and_attentions'])) { // 購入にあたってのお願い・注意事項
            $rules['reason_lesson_buy_and_attentions'] = ['required', 'string', 'max:500'];
            $reason_exist = true;
        }
        if (isset($params['lesson_other']) && !is_null($params['lesson_other'])) { // その他当社が不適切と判断した点
            $rules['reason_lesson_other'] = ['required', 'string', 'max:500'];
            $reason_exist = true;
        }

        if (!$reason_exist) {
            $rules['reason_no_exist'] = ['required', 'string', 'max:500'];
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['reason_lesson_image'] = "理由";
        $attributes['reason_lesson_title'] = "理由";
        $attributes['reason_lesson_service_details'] = "理由";
        $attributes['reason_lesson_other_details'] = "理由";
        $attributes['reason_lesson_buy_and_attentions'] = "理由";
        $attributes['reason_lesson_other'] = "理由";
        $attributes['reason_no_exist'] = "理由";
        $attributes['lesson_image_item'] = "レッスンイメージ";
        /*$attributes['reason_lesson_image'] = "レッスンイメージ";
        $attributes['reason_lesson_title'] = "レッスンタイトル";
        $attributes['reason_lesson_service_details'] = "サービス詳細";
        $attributes['reason_lesson_other_details'] = "持ち物・その他の費用";
        $attributes['reason_lesson_buy_and_attentions'] = "購入にあたってのお願い・注意事項";
        $attributes['reason_lesson_other'] = "その他当社が不適切と判断した点";*/
        return $attributes;
    }

    public function messages()
    {
        return [
            'lesson_image_item.required' => 'レッスンイメージを選択してください。',
            'reason_no_exist.required' => '承認出来ない理由を選択してください。',
            'reason_lesson_image.required' => '承認出来ない理由を入力してください。',
            'reason_lesson_title.required' => '承認出来ない理由を入力してください。',
            'reason_lesson_service_details.required' => '承認出来ない理由を入力してください。',
            'reason_lesson_other_details.required' => '承認出来ない理由を入力してください。',
            'reason_lesson_buy_and_attentions.required' => '承認出来ない理由を入力してください。',
            'reason_lesson_other.required' => '承認出来ない理由を入力してください。'
        ];
    }
}

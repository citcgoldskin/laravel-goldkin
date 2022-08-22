<?php

namespace App\Http\Requests\User;

use App\Rules\User\BirthdayRule;
use Illuminate\Foundation\Http\FormRequest;

class LessonAddRequest extends FormRequest
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
        $rules['lesson_pic_1'] = ['required'];
        $rules['lesson_class_id'] = ['required','integer','min:1'];
        $rules['lesson_title'] = ['required','max:50'];
        //$rules['lesson_wish_sex'] = ['required','min:1'];
        //$rules['lesson_wish_minage'] = ['min:1'];
        //$rules['lesson_wish_maxage'] = ['min:1'];
        $rules['lesson_min_hours'] = ['required'];
        $rules['lesson_30min_fees'] = ['required','integer', 'min:1000',  'max:100000'];
        //$rules['lesson_address_and_keyword'] = ['required'];
        $rules['lesson_pos_detail'] = ['max:100'];
        $rules['lesson_service_details'] = ['max:1000'];
        $rules['lesson_other_details'] = ['max:200'];
        $rules['lesson_buy_and_attentions'] = ['max:200'];
        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['lesson_pic_1'] = "レッスンイメージ";
        $attributes['lesson_class_id'] = "カテゴリー";
        $attributes['lesson_title'] = "レッスンタイトル";
        //$attributes['lesson_wish_sex'] = "希望する性別";
        //$attributes['lesson_wish_minage'] = "希望する年代";
        //$attributes['lesson_wish_maxage'] = "希望する年代";
        $attributes['lesson_min_hours'] = "最低レッスン時間";
        $attributes['lesson_30min_fees'] = "30分あたりのレッスン料金";
        //$attributes['lesson_address_and_keyword'] = "住所やキーワード";
        $attributes['lesson_pos_detail'] = "待ち合わせ場所の詳細";
        $attributes['lesson_service_details'] = "サービス詳細";
        $attributes['lesson_other_details'] = "持ち物・その他の費用";
        $attributes['lesson_buy_and_attentions'] = "購入にあたってのお願い・注意事項";
        return $attributes;
    }

    public function messages()
    {
        return [
            'lesson_title.required' => '性別を正確に入力してください。',
        ];
    }
}

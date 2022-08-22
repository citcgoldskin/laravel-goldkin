<?php

namespace App\Http\Requests\Admin;

use App\Rules\Admin\CategoryRegisterRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class QuestionRequest extends FormRequest
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

        // 公開予約設定 create
        if (isset($params['reserve_create']) && $params['reserve_create'] == 1) {
            $rules['year'] = ['required', 'numeric'];
            $rules['month'] = ['required', 'numeric'];
            $rules['day'] = ['required', 'numeric'];
            $rules['hour'] = ['required', 'numeric'];
            $rules['minute'] = ['required', 'numeric'];

            $now = Carbon::now()->format('Y-m-d H:i:s');
            if (isset($params['year']) && isset($params['month']) && isset($params['day']) && isset($params['hour']) && isset($params['minute'])) {
                $rules['reserve_date'] = ['nullable', 'after:'.$now];
            }
        } else {
            // 新規作成
            $rules['sub_category'] = ['required', 'numeric', 'min:1'];
            $rules['question'] = ['required', 'string'];
            $rules['answer'] = ['required', 'string'];
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['sub_category']  = "サブカテゴリー";
        $attributes['question']  = "質問";
        $attributes['answer']  = "回答";
        $attributes['year']  = "年";
        $attributes['month']  = "月";
        $attributes['day']  = "日";
        $attributes['hour']  = "時";
        $attributes['minute']  = "分";
        $attributes['reserve_date']  = "公開予約日時";
        return $attributes;
    }

    public function messages()
    {
        return [
            'sub_category.required' => 'サブカテゴリーを選択してください。',
            'question.required' => '質問を入力してください。',
            'answer.required' => '回答を入力してください。',
            'reserve_date.after' => '公開予約日時は現在時刻以降から設定可能です。',
        ];
    }
}

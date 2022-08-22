<?php

namespace App\Http\Requests\User;

use App\Rules\User\BirthdayRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class LessonAttendRequest extends FormRequest
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

        $rules['time_flag'] = ['required', 'integer', 'min:1'];
        $rules['hope_time_flag'] = ['required', 'integer', 'min:1'];
        $rules['member_flag'] = ['required', 'integer', 'min:1'];

        if (isset($params['pos_discuss']) && $params['pos_discuss'] == 1) {
            $rules['area_id'] = ['required', 'integer'];
            $rules['address'] = ['required', 'string'];
            $rules['address_detail'] = ['required', 'string', 'max:200'];
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
            'time_flag.required' => '時間を正確に選択してください。',
            'time_flag.min' => '時間を正確に選択してください。',
            'hope_time_flag.required' => '希望日時を正確に選択してください。',
            'hope_time_flag.min' => '希望日時を正確に選択してください。',
            'member_flag.required' => '参加人数を選択してください。',
            'area_id.required' => 'エリアを選択してください。',
            'address.required' => '続きの住所を入力してください。',
            'address_detail.required' => '待ち合わせ場所の詳細を入力してください。',
            'member_flag.min' => '参加人数を選択してください。'
        ];
    }
}

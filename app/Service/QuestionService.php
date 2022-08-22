<?php

namespace App\Service;
use App\Models\Question;
use App\Models\QuestionAccessHistory;
use App\Models\QuestionClass;
use App\Models\QuestionFrequent;
use Carbon\Carbon;
use DB;

class QuestionService extends BaseCateService
{
    public $_table = "question_classes";
    public $_id = "qc_id";
    public $_name = "qc_name";
    public $_parent = "qc_parent";

    public static function doCreateQuestion($data) {

        if($obj_question = Question::create($data))
        {
            return $obj_question;
        } else {
            return null;
        }
    }

    public static function doUpdateQuestion($question, $data) {

        if($obj_question = $question->update($data))
        {
            return $obj_question;
        } else {
            return null;
        }
    }

    public static function doCreateQuestionCategory($params) {
        $obj_question_category = new QuestionClass();

        $obj_question_category->qc_name = $params['category_name'];
        $obj_question_category->qc_public = config('const.question_category_public.no_public');
        $obj_question_category->qc_parent = 0;

        return $obj_question_category->save();
    }

    public static function doCreateQuestionSubCategory($params) {
        $obj_question_category = new QuestionClass();

        $obj_question_category->qc_name = $params['category_name'];
        $obj_question_category->qc_public = config('const.question_category_public.no_public');
        $obj_question_category->qc_parent = $params['category_id'];

        return $obj_question_category->save();
    }

    public static function doDestroyQuestionCategory($params) {
        $obj_question_category = QuestionClass::find($params['del_id']);

        return $obj_question_category->delete();
    }

    public static function doDestroyQuestionSubCategory($params) {
        $obj_question_category = QuestionClass::find($params['del_id']);

        return $obj_question_category->delete();
    }

    // カテゴリーを更新
    public static function doUpdateQuestionCategory($params) {
        $category_names = $params['qc_name'];
        $category_public = $params['qc_public'];
        foreach ($category_names as $key=>$value) {
            $obj_question_category = QuestionClass::find($key);
            $obj_question_category->qc_name = $value;
            $obj_question_category->qc_public = $category_public[$key];
            $obj_question_category->save();
        }

        return true;
    }

    // よく見られている質問並び替え
    public static function doUpdateNormalQuestionSort($params) {
        $question_frequent_arr = $params['category_sorts'];
        foreach ($question_frequent_arr as $key=>$value) {
            $obj_question_category = QuestionFrequent::find($value);
            $obj_question_category->sort = $key + 1;
            $obj_question_category->save();
        }

        return true;
    }

    // カテゴリー並び替え
    public static function doUpdateQuestionCategorySort($params) {
        $category_sort_arr = $params['category_sorts'];
        foreach ($category_sort_arr as $key=>$value) {
            $obj_question_category = QuestionClass::find($value);
            $obj_question_category->qc_sort = $key + 1;
            $obj_question_category->save();
        }

        return true;
    }

    // サブカテゴリー並び替え
    public static function doUpdateQuestionSubCategorySort($params) {
        $category_sort_arr = $params['category_sorts'];
        foreach ($category_sort_arr as $key=>$value) {
            $obj_question_category = QuestionClass::find($value);
            $obj_question_category->qc_sort = $key + 1;
            $obj_question_category->save();
        }

        return true;
    }

    public static function getQuestiones($name)
    {
        $que_class_obj = QuestionClass::where('qc_name', $name)->first();
        if (is_object($que_class_obj)) {
            return Question::where('que_qc_id', $que_class_obj['qc_id'])
                ->get()->toArray();
        }
        return array();
    }

    public static function getQuestionFromId($id)
    {
        $obj_question = Question::where('que_id', $id)->first();
        $category = $obj_question->question_parent_category;
        $ret = $obj_question->toArray();
        $ret['category_id'] = $category;
        return $ret;
        /*return Question::where('que_id', $id)->first()->toArray();*/
    }

    public static function getQuestionsWithAnswerFromQcId($qc_id)
    {
        return Question::where('que_qc_id', $qc_id)
            ->where('que_ask', '!=',null)
            ->where('que_answer', '!=',null)
            ->get();
    }

    //$freq = 0 : any frequent, 1 : frequent
    public function getQuesFromParent($class_id, $freq)
    {
        $questions = Question::orderByDesc('que_frequent');

        if($freq > 0)
        {
            $questions->where('que_frequent', $freq);
        }

        if($class_id > 0)
        {
            $class_array = $this->getAllChildren($class_id);
            $questions->whereIn('que_qc_id', $class_array);
        }

        return $questions;
    }

    public static function getQuesClassFromParent($parent_id)
    {
        return QuestionClass::orderBy('qc_sort')
            ->where('qc_parent', $parent_id)
            ->get()
            ->toArray();
    }

    public static function getQuesClassFromParentAdmin($parent_id)
    {
        return QuestionClass::where('qc_parent', $parent_id)
            ->orderBy('qc_sort')
            ->orderBy('created_at')
            ->get()
            ->toArray();
    }

    public static function getQuesClassFromId($id)
    {
        return QuestionClass::where('qc_id', $id)
            ->get()
            ->toArray();
    }

    public static function doSearchQuestion($data)
    {
        $keyword = $data["keyword"];
        $id = $data["id"];

        $questions = Question::orderByDesc('que_frequent');
        if($keyword != "")
        {
            $questions->where('que_ask', 'like', '%' . $keyword . '%')
            ->orWhere('que_answer', 'like', '%' . $keyword . '%');
        }

        if($id > 0)
        {
            $questions->where('que_qc_id', $id);
        }

        return $questions;
    }

    public static function doSearchReserveQuestionAdmin($condition)
    {
        $questions = Question::orderBy('que_public_at');
        $now = Carbon::now()->format('Y-m-d H:i:s');
        if (isset($condition['reserve_type']) && $condition['reserve_type'] == config('const.reserve_type_code.public')) {
            $questions->whereNotNull('que_public_at');
            $questions->where('que_public_at', '>', $now);
            $questions->where('que_public', config('const.question_category_public.no_public'));
        }
        if (isset($condition['reserve_type']) && $condition['reserve_type'] == config('const.reserve_type_code.update')) {
            $questions->whereNotNull('que_update_at');
            $questions->where('que_update_at', '>', $now);
        }
        if (isset($condition['reserve_type']) && $condition['reserve_type'] == config('const.reserve_type_code.delete')) {
            $questions->whereNotNull('que_delete_at');
            $questions->where('que_delete_at', '>', $now);
        }

        $questions->where('que_status', config('const.question_status.register'));

        $questions->whereRaw(
            'que_qc_id in ('.DB::raw('select qc_id from question_classes where deleted_at is null and qc_parent in (select qc_id from question_classes where deleted_at is null and qc_parent = 0)').')'
        );

        return $questions;
    }

    public static function doSearchQuestionAdmin($condition)
    {

        $questions = Question::orderByDesc('que_frequent');
        if(isset($condition['category'])) {
            if ($condition['category'] != 'all') {
                if (isset($condition['sub_category']) && $condition['sub_category'] != 'all') {
                    $questions->where('que_qc_id', $condition['sub_category']);
                } else {
                    $questions->whereRaw(
                        'que_qc_id in ('.DB::raw('select qc_id from question_classes where qc_parent='.$condition['category']).')'
                    );
                }
            }
        }

        $questions->where('que_status', config('const.question_status.register'));
        $questions->where('que_public', config('const.question_category_public.public'));

        // validate category and subcategory's deleted_at
        $questions->whereRaw(
            'que_qc_id in ('.DB::raw('select qc_id from question_classes where deleted_at is null and qc_parent in (select qc_id from question_classes where deleted_at is null and qc_parent = 0)').')'
        );

        return $questions;
    }

    public static function getQuestionClassName($category_id)
    {
        $obj_question = QuestionClass::where('qc_id', $category_id)->first();
        return is_object($obj_question) ? $obj_question->qc_name : '';
    }

    public static function getNormalQuestion($frequent_type=null)
    {
        $questions = QuestionFrequent::orderBy('question_frequents.sort');
        $questions->leftJoin('questions', function ($join) {
            $join->on('question_frequents.question_id', 'questions.que_id');
            $join->whereNull('questions.deleted_at');
        });
        if(isset($frequent_type)) {
            if ($frequent_type == 'all') {
                $questions->where('question_frequents.class', 0);
            } else {
                $questions->where('question_frequents.class', $frequent_type);
            }
        }

        $questions->where('questions.que_status', config('const.question_status.register'));
        $questions->where('questions.que_public', config('const.question_category_public.public'));

        // validate category and subcategory's deleted_at
        $questions->whereRaw(
            'questions.que_qc_id in ('.DB::raw('select qc_id from question_classes where deleted_at is null and qc_parent in (select qc_id from question_classes where deleted_at is null and qc_parent = 0)').')'
        );

        return $questions;
    }

    public static function getQuestionsForFrequentByCategory($sub_category_id, $frequent_type, $frequent_edit_id=null)
    {
        $questions =  Question::where('que_qc_id', $sub_category_id);
        if (!is_null($frequent_edit_id) && $frequent_edit_id) {
            // よく見られている質問 edit (include self)
            $questions->whereRaw(
                'que_id not in ('.DB::raw('select question_id from question_frequents where id != '.$frequent_edit_id.' and deleted_at is null and class = '.($frequent_type == 'all' ? 0 : $frequent_type)).')'
            );
        } else {
            // よく見られている質問 new
            $questions->whereRaw(
                'que_id not in ('.DB::raw('select question_id from question_frequents where deleted_at is null and class = '.($frequent_type == 'all' ? 0 : $frequent_type)).')'
            );
        }

        $questions->where('que_status', config('const.question_status.register'))
            ->where('que_public', config('const.question_category_public.public'))
            ->whereRaw(
                'que_qc_id in ('.DB::raw('select qc_id from question_classes where deleted_at is null and qc_parent in (select qc_id from question_classes where deleted_at is null and qc_parent = 0)').')'
            );
        return $questions;
    }

    public static function doSearchNoPublicQuestionAdmin()
    {
        $ret =  Question::orderByDesc('updated_at');
        $ret->where(function($q) {
            // 下書き
            $q->orWhere(function($q1) {
                $q1->whereNull('que_public_at');
                $q1->where('que_status', config('const.question_status.draft'));
            });
            // 変下書き
            $q->orWhere(function($q1) {
                $q1->whereNotNull('que_public_at');
                $q1->where('que_status', config('const.question_status.draft'));
            });
            // 非公開
            $q->orWhere('que_public', config('const.question_category_public.no_public'));
        });
        return $ret;
    }

    public static function doDeleteQuestion($ids_arr)
    {
        return Question::whereIn('que_id', $ids_arr)->delete();
    }

    public static function doSetQuestionFrequent($condition)
    {
        $obj_question_frequent = new QuestionFrequent();
        if (isset($condition['frequent_id']) && $condition['frequent_id']) {
            $obj_question_frequent = QuestionFrequent::find($condition['frequent_id']);
        }

        $obj_question_frequent->question_id = $condition['question_id'];
        $obj_question_frequent->class = $condition['frequent_type'] == 'all' ? 0 : $condition['frequent_type'];

        return $obj_question_frequent->save();
    }

    // よくある質問=>アクセス数
    public static function setAccessQuestion($user, $question_id)
    {
        $obj_access = new QuestionAccessHistory();
        if (!is_null($user) && is_object($user)) {
            $obj_access->user_id = $user->id;
        }
        $obj_access->question_id = $question_id;
        return $obj_access->save();
    }

    public static function getQuestionAccessInfo()
    {
        $ret = [];
        // アクセス数

        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        $yesterday = Carbon::now()->addDays(-1)->format('Y-m-d');
        $q_yesterday = QuestionAccessHistory::whereDate('created_at', $yesterday)
            ->get()
            ->count();
        $q_month = QuestionAccessHistory::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get()
            ->count();

        $q_total = QuestionAccessHistory::orderBy('created_at')
            ->get()
            ->count();
        $ret['question_access'] = [
            'yesterday'=>$q_yesterday,
            'this_month'=>$q_month,
            'total'=>$q_total
        ];
        // 問い合わせ件数

        return $ret;
    }

}

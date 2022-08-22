<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\QuestionRequest;
use App\Models\Question;
use App\Service\LessonClassService;
use App\Service\LessonService;
use App\Service\QuestionService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Storage;
use Session;
use DB;

class FreqQuestionController extends AdminController
{
    public function index(Request $request)
    {
        $params = $request->all();

        $access_info = QuestionService::getQuestionAccessInfo();
        return view('admin.freq.index', [
            'access_info'=>$access_info
        ]);
    }

    public function question(Request $request)
    {
        $params = $request->all();
        $del_session = $request->input('del_session', 0);
        if ($del_session) {
            Session::forget('admin.freq.question');
        }
        $condition = Session::get('admin.freq.question', []);

        if (isset($params['category']) && $params['category']) {
            $condition['category'] = $params['category'];
        }

        if (isset($params['sub_category']) && $params['sub_category']) {
            $condition['sub_category'] = $params['sub_category'];
        }

        Session::put('admin.freq.question', $condition);

        $categories = QuestionService::getQuesClassFromParentAdmin(0);
        $sub_categories = [];
        if (isset($condition['category']) && $condition['category'] && $condition['category'] != 'all') {
            $sub_categories = QuestionService::getQuesClassFromParentAdmin($condition['category']);
        }

        $questions = QuestionService::doSearchQuestionAdmin($condition)->paginate($this->per_page);

        return view('admin.freq.question.index', [
            'questions' => $questions,
            'params' => $condition,
            'categories' => $categories,
            'sub_categories' => $sub_categories
        ]);
    }

    public function questionDetail(Request $request, Question $question)
    {
        Session::forget('admin.freq.question.reserve');

        return view('admin.freq.question.detail', [
            'question' => $question
        ]);
    }

    public function getQuestionAjax(Request $request)
    {
        $result = [];
        $frequent_type= $request->input('frequent_type', 0);
        $category_id= $request->input('category_id', 0);
        $sub_category_id= $request->input('sub_category_id', 0);
        $frequent_id= $request->input('frequent_id', 0);

        $condition = Session::get('admin.freq.normal_question', []);
        $condition['category'] = $category_id;
        $condition['sub_category'] = $sub_category_id;

        Session::put('admin.freq.normal_question', $condition);

        if ($sub_category_id) {
            $result = QuestionService::getQuestionsForFrequentByCategory($sub_category_id, $frequent_type, $frequent_id)->paginate(10);
        }

        return response()->json(
            [
                "result_code" => $sub_category_id ? "success" : "fail",
                "result" => view('admin.freq.normal_question.question_list', ['frequent_type'=>$frequent_type, 'questions'=>$result, 'frequent_id'=>$frequent_id])->render()
            ]);
    }

    // このQ&Aを非公開にする
    public function doQuestionNoPublic(Request $request)
    {
        $params = $request->all();
        $contents = "非公開にしました。";
        $page_from = "";
        if (isset($params['page_from'])) {
            $page_from = $params['page_from'];
        }

        $modal_confrim_url = route('admin.freq.question');

        // 下書き・非公開
        if ($page_from == "no_public_question") {
            $modal_confrim_url = route('admin.freq.no_public_question');
        }

        $obj_question = Question::find($params['question_id']);
        $params = [
            'que_public' => config('const.question_category_public.no_public')
        ];
        if (!QuestionService::doUpdateQuestion($obj_question, $params)) {
            $contents = "操作に失敗しました。";
            $modal_confrim_url = route('admin.freq.question');
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

    public function questionEdit(Request $request, Question $question)
    {
        $categories = QuestionService::getQuesClassFromParentAdmin(0);
        $question_info = [
            'category' => $question->question_parent_category,
            'sub_category' => $question->que_qc_id,
            'question' => $question->que_ask,
            'answer' => $question->que_answer,
        ];
        $params = Session::get('admin.freq.question.reserve', $question_info);

        return view('admin.freq.question.edit', [
            'question' => $question,
            'params' => $params,
            'categories' => $categories
        ]);
    }

    // Q&A編集
    public function doQuestionEdit(QuestionRequest $request)
    {
        $params = $request->all();
        $contents = "変更を確定しました。";
        $page_from = "";
        if (isset($params['page_from'])) {
            $page_from = $params['page_from'];
        }
        $modal_confrim_url = route('admin.freq.question');

        // 下書き・非公開
        if ($page_from == "no_public_question") {
            $modal_confrim_url = route('admin.freq.no_public_question');
        }

        $obj_question = Question::find($params['question_id']);
        $params = [
            'que_qc_id' => $params['sub_category'],
            'que_ask' => $params['question'],
            'que_answer' => $params['answer']
        ];
        if (!QuestionService::doUpdateQuestion($obj_question, $params)) {
            $contents = "操作に失敗しました。";
            $modal_confrim_url = route('admin.freq.new_question');
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

    // Q&A編集=>下書きとして保存
    public function draftQuestion(Request $request)
    {
        $params = $request->all();
        $contents = "下書きを保存しました。";
        /*$page_from = "";
        if (isset($params['page_from'])) {
            $page_from = $params['page_from'];
        }*/

        $modal_confrim_url = route('admin.freq.question');

        // 下書き・非公開
        /*if ($page_from == "no_public_question") {
            $modal_confrim_url = route('admin.freq.no_public_question');
        }*/

        $obj_question = Question::find($params['question_id']);
        $params = [
            'que_qc_id' => isset($params['sub_category']) ? $params['sub_category'] : null,
            'que_ask' => isset($params['question']) ? $params['question'] : null,
            'que_answer' => isset($params['answer']) ? $params['answer'] : null,
            'que_status' => config('const.question_status.draft'),
            'que_public' => config('const.question_category_public.no_public')
        ];
        if (!QuestionService::doUpdateQuestion($obj_question, $params)) {
            $contents = "操作に失敗しました。";
            $modal_confrim_url = route('admin.freq.question');
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

    // 変更予約をして登録する get
    public function questionReserve(Request $request, Question $question)
    {
        $params = Session::get('admin.freq.question.reserve');
        return view('admin.freq.question.reserve', [
            'question' => $question,
            'params' => $params
        ]);
    }

    // 変更予約をして登録する post
    public function doQuestionReserve(QuestionRequest $request)
    {
        $params = $request->all();
        if (isset($params['_token'])) {
            unset($params['_token']);
        }

        // 下書き・非公開
        if (isset($params['page_from']) && $params['page_from'] == "no_public_question") {
            Session::put('admin.freq.no_public_question.reserve', $params);
            return redirect()->route('admin.freq.no_public_question.reserve_question', ['question'=>$params['question_id']]);
        }

        Session::put('admin.freq.question.reserve', $params);
        return redirect()->route('admin.freq.question.reserve_question', ['question'=>$params['question_id']]);
    }

    // 変更予約をして登録する create
    public function reserveQuestionCreate(QuestionRequest $request)
    {
        $params = $request->all();

        $question_params = Session::get('admin.freq.question.reserve');

        $page_from = isset($params['page_from']) && $params['page_from'] ? $params['page_from'] : '';
        if ($page_from == "no_public_question") {
            $question_params = Session::get('admin.freq.no_public_question.reserve');
        }

        $obj_question = Question::find($params['question_id']);
        $reserve_date = Carbon::parse($params['reserve_date'])->format('Y-m-d H:i:s');
        $question_params['reserve_date'] = $reserve_date;
        $contents = "予定を完了しました。";
        $modal_confrim_url = route('admin.freq.question');

        if ($page_from == "no_public_question") {
            $modal_confrim_url = route('admin.freq.no_public_question');
        }

        $question = [
            'que_status' => config('const.question_status.register'),
            'que_update_at' => $reserve_date,
            'que_update_data' => json_encode([
                'que_qc_id' => $question_params['sub_category'],
                'que_ask' => $question_params['question'],
                'que_answer' => $question_params['answer']
            ], JSON_UNESCAPED_UNICODE),
        ];
        if (!QuestionService::doUpdateQuestion($obj_question, $question)) {
            $contents = "操作に失敗しました。";
            $modal_confrim_url = route('admin.freq.question.reserve_question', ['question'=>$params['question_id']]);
            if ($page_from == "no_public_question") {
                $modal_confrim_url = route('admin.freq.no_public_question.reserve_question', ['question'=>$params['question_id']]);
            }
        }

        Session::forget('admin.freq.question.reserve');
        if ($page_from == "no_public_question") {
            Session::forget('admin.freq.no_public_question.reserve');
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

    public function newQuestion(Request $request)
    {
        $categories = QuestionService::getQuesClassFromParentAdmin(0);
        $del_session = $request->input('del_session', 0);
        if ($del_session) {
            Session::forget('admin.freq.new_question.reserve');
        }
        $params = Session::get('admin.freq.new_question.reserve', []);

        return view('admin.freq.new_question.index', [
            'params' => $params,
            'categories' => $categories
        ]);
    }

    // 下書き・非公開一覧
    public function noPublicQuestion(Request $request)
    {
        $questions = QuestionService::doSearchNoPublicQuestionAdmin()->paginate(20);
        return view('admin.freq.no_public_question.index', [
            'questions' => $questions,
        ]);
    }

    public function noPublicQuestionDetail(Request $request, Question $question)
    {
        return view('admin.freq.no_public_question.detail', [
            'question' => $question,
        ]);
    }

    public function noPublicQuestionDelete(Request $request)
    {
        $chk_question_ids = $request->input('chk_question');
        if (!QuestionService::doDeleteQuestion(array_keys($chk_question_ids))) {
            $request->session()->flash('error', '削除に失敗しました。');
            return back();
        }

        $request->session()->flash('success', '内容を削除しました。');
        return redirect()->route('admin.freq.no_public_question');

    }

    public function noPublicQuestionEdit(Request $request, Question $question)
    {
        $del_session = $request->input('del_session', 0);
        if ($del_session) {
            Session::forget('admin.freq.no_public_question.reserve');
        }

        $categories = QuestionService::getQuesClassFromParentAdmin(0);
        $question_info = [
            'category' => $question->question_parent_category,
            'sub_category' => $question->que_qc_id,
            'question' => $question->que_ask,
            'answer' => $question->que_answer,
        ];

        $params = Session::get('admin.freq.no_public_question.reserve', $question_info);

        return view('admin.freq.no_public_question.edit', [
            'question' => $question,
            'params' => $params,
            'categories' => $categories
        ]);
    }

    public function noPublicQuestionReserve(Request $request, Question $question)
    {
        $params = Session::get('admin.freq.no_public_question.reserve');
        return view('admin.freq.question.reserve', [
            'question' => $question,
            'params' => $params
        ]);
    }

    // 各予約一覧
    public function reserveInfo(Request $request)
    {
        $params = $request->all();
        $del_session = $request->input('del_session', 0);
        if ($del_session) {
            Session::forget('admin.freq.reserve_question');
        }
        $condition = Session::get('admin.freq.reserve_question', []);

        if (isset($params['reserve_type']) && $params['reserve_type']) {
            $condition['reserve_type'] = $params['reserve_type'];
        } else if(isset($condition['reserve_type']) && $condition['reserve_type']) {
            //
        } else {
            $condition['reserve_type'] = config('const.reserve_type_code.public');
        }

        Session::put('admin.freq.reserve_question', $condition);

        $questions = QuestionService::doSearchReserveQuestionAdmin($condition)->paginate(20);

        return view('admin.freq.reserve_question.index', [
            'params' => $condition,
            'questions' => $questions
        ]);
    }

    public function reserveQuestionEdit(Request $request, Question $question)
    {
        $reserve_type = $request->input('reserve_type');

        if ($reserve_type == config('const.reserve_type_code.update')) {
            $update_data = json_decode($question->que_update_data, true);
            $question->que_qc_id = $update_data['que_qc_id'];
            $question->que_ask = $update_data['que_ask'];
            $question->que_answer = $update_data['que_answer'];
        }

        $categories = QuestionService::getQuesClassFromParentAdmin(0);
        $params = [
            'category_id' => $question->question_parent_category,
            'category_name' => $question->question_parent_category_name,
            'sub_category' => $question->que_qc_id,
            'question' => $question->que_ask,
            'answer' => $question->que_answer
        ];

        return view('admin.freq.reserve_question.edit', [
            'question' => $question,
            'reserve_type' => $reserve_type,
            'params' => $params,
            'categories' => $categories
        ]);
    }

    public function reserveQuestionUpdate(Request $request, Question $question)
    {
        $reserve_type = $request->input('reserve_type');

        if ($reserve_type == config('const.reserve_type_code.update')) {
            $update_data = json_decode($question->que_update_data, true);
            $question->que_qc_id = $update_data['que_qc_id'];
            $question->que_ask = $update_data['que_ask'];
            $question->que_answer = $update_data['que_answer'];
        }

        $categories = QuestionService::getQuesClassFromParentAdmin(0);
        $params = [
            'category_id' => $question->question_parent_category,
            'category_name' => $question->question_parent_category_name,
            'sub_category' => $question->que_qc_id,
            'question' => $question->que_ask,
            'answer' => $question->que_answer
        ];

        return view('admin.freq.reserve_question.update', [
            'question' => $question,
            'reserve_type' => $reserve_type,
            'params' => $params,
            'categories' => $categories
        ]);
    }

    public function doReserveQuestionUpdate(QuestionRequest $request)
    {
        $params = $request->all();
        $obj_question = Question::find($params['question_id']);
        $params_update = [
            'que_qc_id' => $params['sub_category'],
            'que_ask' => $params['question'],
            'que_answer' => $params['answer']
        ];

        if ($params['reserve_type'] == config('const.reserve_type_code.update')) {
            $params_update = [
                'que_update_data' => json_encode([
                    'que_qc_id' => $params['sub_category'],
                    'que_ask' => $params['question'],
                    'que_answer' => $params['answer']
                ], JSON_UNESCAPED_UNICODE)
            ];
        }

        if (!QuestionService::doUpdateQuestion($obj_question, $params_update)) {
            $request->session()->flash('error', '内容変更に失敗しました。');
            return back();
        }

        $request->session()->flash('success', '内容を変更しました。');
        return redirect()->route('admin.freq.reserve_question.edit', ['question'=>$obj_question->que_id, 'reserve_type'=>$params['reserve_type']]);
    }

    public function reserveQuestionDateChange(Request $request, Question $question)
    {
        // $params = Session::get('admin.freq.question.reserve');
        $reserve_type = $request->input('reserve_type');

        if ($reserve_type == config('const.reserve_type_code.update')) {
            $update_data = json_decode($question->que_update_data, true);
            $question->que_qc_id = $update_data['que_qc_id'];
            $question->que_ask = $update_data['que_ask'];
            $question->que_answer = $update_data['que_answer'];
        }

        $params = [
            'category' => $question->question_parent_category,
            'sub_category' => $question->que_qc_id,
            'question' => $question->que_ask,
            'answer' => $question->que_answer,
        ];

        return view('admin.freq.reserve_question.change', [
            'question' => $question,
            'reserve_type' => $reserve_type,
             'params' => $params
        ]);
    }

    public function doReserveQuestionDateChange(Request $request)
    {
        $params = $request->all();
        $reserve_type = $params['reserve_type'];
        $obj_question = Question::find($params['question_id']);
        $question = [
            'que_public_at' => $params['reserve_date'],
        ];
        if ($reserve_type == config('const.reserve_type_code.update')) {
            $question = [
                'que_update_at' => $params['reserve_date'],
            ];
        } else if($reserve_type == config('const.reserve_type_code.delete')) {
            $question = [
                'que_delete_at' => $params['reserve_date'],
            ];
        }
        if (!QuestionService::doUpdateQuestion($obj_question, $question)) {
            $request->session()->flash('error', '内容変更に失敗しました。');
            return back();
        }

        $request->session()->flash('success', '日時を変更しました。');
        return redirect()->route('admin.freq.reserve_question.edit', ['question'=>$obj_question->que_id, 'reserve_type'=>$reserve_type]);
    }

    // よく見られている質問管理
    public function normalQuestion(Request $request)
    {
        $params = $request->all();
        $categories = QuestionService::getQuesClassFromParentAdmin(0);

        return view('admin.freq.normal_question.index', [
            'categories'=>$categories
        ]);
    }

    public function normalQuestionDetail(Request $request, $frequent_type)
    {
        $question_frequents = QuestionService::getNormalQuestion($frequent_type)->paginate(10);
        return view('admin.freq.normal_question.detail', [
            'frequent_type'=>$frequent_type,
            'question_frequents'=>$question_frequents,
        ]);
    }

    public function normalQuestionAdd(Request $request, $frequent_type)
    {
        $params = $request->all();
        $del_session = $request->input('del_session', 0);
        if ($del_session) {
            Session::forget('admin.freq.normal_question');
        }
        $condition = Session::get('admin.freq.normal_question', []);

        if (isset($params['category']) && $params['category']) {
            $condition['category'] = $params['category'];
        }

        if ($frequent_type != 'all') {
            $condition['category'] = $frequent_type;
        }

        if (isset($params['sub_category']) && $params['sub_category']) {
            $condition['sub_category'] = $params['sub_category'];
        }

        Session::put('admin.freq.normal_question', $condition);

        $categories = QuestionService::getQuesClassFromParentAdmin(0);
        $sub_categories = [];

        if (isset($condition['category']) && $condition['category'] && $condition['category'] != 'all') {
            $sub_categories = QuestionService::getQuesClassFromParentAdmin($condition['category']);
        }

        $questions = null;
        if (isset($condition['sub_category']) && $condition['sub_category']) {
            $questions = QuestionService::getQuestionsForFrequentByCategory($condition['sub_category'], $frequent_type)->paginate(20);
        }

        return view('admin.freq.normal_question.add', [
            'frequent_type'=>$frequent_type,
            'questions'=>$questions,
            'params' => $condition,
            'sub_categories'=>$sub_categories,
            'categories'=>$categories
        ]);
    }

    public function normalQuestionContent(Request $request, $frequent_type, Question $question)
    {
        $frequent_id = $request->input('frequent_id', 0);
        return view('admin.freq.normal_question.question_detail', [
            'frequent_type' => $frequent_type,
            'frequent_id' => $frequent_id,
            'question' => $question
        ]);
    }

    public function normalQuestionContentData(Request $request, $frequent_type, Question $question)
    {

        return view('admin.freq.normal_question.content_data', [
            'frequent_type' => $frequent_type,
            'question' => $question
        ]);
    }

    public function normalQuestionAddFrequent(Request $request)
    {
        $frequent_type = $request->input('frequent_type');
        $question_id = $request->input('question_id');
        $frequent_id = $request->input('frequent_id');
        if (!QuestionService::doSetQuestionFrequent([
            'frequent_type'=>$frequent_type,
            'frequent_id'=>$frequent_id,
            'question_id'=>$question_id
        ])) {
            $request->session()->flash('error', '操作に失敗しました。');
            return back();
        }
        return redirect()->route('admin.freq.normal_question.detail', ['frequent_type'=>$frequent_type]);
    }

    public function normalQuestionSort(Request $request, $frequent_type)
    {
        $params = $request->all();

        $question_frequents = QuestionService::getNormalQuestion($frequent_type)->paginate(10);

        return view('admin.freq.normal_question.sort_category', [
            'frequent_type' => $frequent_type,
            'question_frequents' => $question_frequents
        ]);
    }

    public function setNormalQuestionSort(Request $request)
    {
        $params = $request->all();
        if (QuestionService::doUpdateNormalQuestionSort($params)) {
            $request->session()->flash('success', '並びかえを更新しました。');
            return redirect()->route('admin.freq.normal_question.detail', ['frequent_type'=>$params['frequent_type']]);
        }

        $request->session()->flash('error', '操作に失敗しました。');
        return back();
    }

    public function normalQuestionChange(Request $request, $frequent_type, Question $question)
    {
        $params = $request->all();
        $frequent_id = $request->input('frequent_id', 0);
        $del_session = $request->input('del_session', 0);
        if ($del_session) {
            Session::forget('admin.freq.normal_question.change');
        }
        $condition = Session::get('admin.freq.normal_question.change', []);

        if (isset($params['category']) && $params['category']) {
            $condition['category'] = $params['category'];
        } else {
            $condition['category'] = $question->question_parent_category;
        }

        if (isset($params['sub_category']) && $params['sub_category']) {
            $condition['sub_category'] = $params['sub_category'];
        } else {
            $condition['sub_category'] = $question->que_qc_id;
        }

        Session::put('admin.freq.normal_question.change', $condition);

        $categories = QuestionService::getQuesClassFromParentAdmin(0);
        $sub_categories = [];

        if (isset($condition['category']) && $condition['category'] && $condition['category'] != 'all') {
            $sub_categories = QuestionService::getQuesClassFromParentAdmin($condition['category']);
        }

        $questions = null;
        if (isset($condition['sub_category']) && $condition['sub_category']) {
            $questions = QuestionService::getQuestionsForFrequentByCategory($condition['sub_category'], $frequent_type, $frequent_id)->paginate(20);
        }

        return view('admin.freq.normal_question.add', [
            'frequent_id'=>$frequent_id,
            'frequent_type'=>$frequent_type,
            'questions'=>$questions,
            'params' => $condition,
            'sub_categories'=>$sub_categories,
            'categories'=>$categories
        ]);
    }

    // カテゴリー管理
    public function questionCategory(Request $request)
    {
        $params = $request->all();

        return view('admin.freq.category.index', [
        ]);
    }

    // 新規登録しすぐ公開する
    public function createNewQuestion(QuestionRequest $request)
    {
        $params = $request->all();
        $contents = "Q&Aを新規に公開しました。";
        $modal_confrim_url = route('admin.freq.index');
        $question = [
            'que_qc_id' => $params['sub_category'],
            'que_ask' => $params['question'],
            'que_answer' => $params['answer'],
            'from_user_id' => 0,
            'que_public' => config('const.question_category_public.public'),
            'que_public_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        if (!QuestionService::doCreateQuestion($question)) {
            $contents = "操作に失敗しました。";
            $modal_confrim_url = route('admin.freq.new_question');
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

    // 下書きとして保存
    public function draftNewQuestion(Request $request)
    {
        $params = $request->all();
        $contents = "下書きを保存しました。";
        $modal_confrim_url = route('admin.freq.index');
        $question = [
            'que_qc_id' => isset($params['sub_category']) ? $params['sub_category'] : null,
            'que_ask' => isset($params['question']) ? $params['question'] : null,
            'que_answer' => isset($params['answer']) ? $params['answer'] : null,
            'que_status' => config('const.question_status.draft'),
        ];
        if (!QuestionService::doCreateQuestion($question)) {
            $contents = "操作に失敗しました。";
            $modal_confrim_url = route('admin.freq.new_question');
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

    // 公開予約をして登録する post
    public function reserveNewQuestion(QuestionRequest $request)
    {
        $params = $request->all();
        if (isset($params['_token'])) {
            unset($params['_token']);
        }
        Session::put('admin.freq.new_question.reserve', $params);
        return redirect()->route('admin.freq.new_question.reserve_question');
    }

    // 公開予約をして登録する get
    public function reserveQuestion(Request $request)
    {
        $params = Session::get('admin.freq.new_question.reserve');
        return view('admin.freq.new_question.reserve', [
            'params' => $params
        ]);
    }

    // 公開予約をして登録する create
    public function reserveNewQuestionCreate(QuestionRequest $request)
    {
        $question_params = Session::get('admin.freq.new_question.reserve');
        $params = $request->all();
        $reserve_date = Carbon::parse($params['reserve_date'])->format('Y-m-d H:i:s');
        $question_params['reserve_date'] = $reserve_date;
        $contents = "予定を完了しました。";
        $modal_confrim_url = route('admin.freq.index');
        $question = [
            'que_qc_id' => $question_params['sub_category'],
            'que_ask' => $question_params['question'],
            'que_answer' => $question_params['answer'],
            'from_user_id' => 0,
            'que_public' => config('const.question_category_public.no_public'),
            'que_public_at' => $reserve_date,
        ];
        if (!QuestionService::doCreateQuestion($question)) {
            $contents = "操作に失敗しました。";
            $modal_confrim_url = route('admin.freq.new_question');
        }

        Session::forget('admin.freq.new_question.reserve');

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

}

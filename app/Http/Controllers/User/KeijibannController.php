<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Service\RecruitVisitService;
use App\Service\SettingService;
use Illuminate\Http\Request;
use App\Models\Recruit;
use App\Models\Question;
use App\Models\LessonClass;
use App\Models\Senpai;
use App\Models\User;
use App\Service\KeijibannService;
use App\Service\LessonClassService;
use App\Service\FavouriteService;
use App\Service\AreaService;
use App\Service\TimeDisplayService;
use App\Service\ProposalService;
use App\Service\CommonService;
use App\Service\LessonService;
use App\Service\UserService;
use App\Service\MessageService;
use function PHPUnit\Framework\isNull;
use function Sodium\crypto_pwhash_scryptsalsa208sha256;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Validator;
use Session;
use App\Http\Requests\User\RecruitRequest;
use App\Http\Requests\User\ProposalRequest;
use App\Http\Requests\User\RecruitConditionRequest;
use Auth;
use DB;

class KeijibannController extends Controller
{
    protected $per_page = 10;

    public function __construct()
    {
    }

    public function index(Request $request, $province_id = 0)
    {
        $login = 0;
        if(isset(Auth::user()->id))
        {
            $login = Auth::user()->id;
        }

        $search_params_cond = Session::get("keijibann.list.condition");
        if(!isset($search_params_cond['order']))
        {
            $search_params_cond['order'] = config('const.recruit_order.new');
            Session::put("keijibann.list.condition", $search_params_cond);
        }
        $search_params_cond['user_id'] = $login;

        if($province_id == 0){
            if (isset($search_params_cond['province_id'])) {
                $province_id = $search_params_cond['province_id'];
            }
        }
        if($province_id > 0){
            $province_name = AreaService::getOneAreaFullName($province_id);
        }else{
            $province_name = 'すべて';
        }

        $recruits = KeijibannService::doSearchRecruit($search_params_cond);

        $recruits = $recruits->paginate($this->per_page);
        $total_cnt = $recruits->total();

        $result = $recruits;//->toArray()['data'];

        foreach($result as $i=>$val)
        {
            $result[$i]['date'] = CommonService::getMd($val['rc_date']);
            $result[$i]['start_end_time'] = CommonService::getStartAndEndTime($val['rc_start_time'], $val['rc_end_time']);
            $result[$i]['age'] = isset($val['cruitUser']) ? CommonService::getAge($val['cruitUser']['user_birthday']) : '';
            $result[$i]["sex"] = isset($val['cruitUser']) ? CommonService::getSexStr($val['cruitUser']['user_sex']) : '';
            $result[$i]['date_recruit'] = TimeDisplayService::getDateFromDatetime($val['created_at']);
            $result[$i]['period_futre'] = CommonService::getDateRemain($val['rc_period']);
            $result[$i]['login'] = $login;

            if($login > 0)
            {
                $fav = FavouriteService::getFavouriteRecruit($val['rc_id'], $login);
                if($fav)
                {
                    $result[$i]['voted'] = 1;
                } else
                {
                    $result[$i]['voted'] = 0;
                }

                // 各投稿 (既読と未読の区別ができるよ うに)
                $recruit_visit = RecruitVisitService::getVisitInfo($val['rc_id'], $login);
                if(is_object($recruit_visit) && count($recruit_visit) > 0)
                {
                    $result[$i]['is_visited'] = 1;
                } else
                {
                    $result[$i]['is_visited'] = 0;
                }
            }
        }

        return view('user.keijibann.home', ['page_id' => 'keijibann',
            'page_id_02' => '',
            'recruits' => $result,
            'pages'=>$recruits,
            'total_cnt' => $total_cnt,
            'province_name' => $province_name,
            'search_params' => $search_params_cond
            ]);
    }

    public function condition(Request $request, $cnt, $province_id = 0)
    {
        $categories  = LessonClassService::getAllLessonClasses();
        $cate_names = "";
        $search_params = Session::get('keijibann.list.condition');


        if(isset($search_params['category_id']))
        {
            $cat_ids = $search_params['category_id'];
            foreach($cat_ids as $key=>$val)
            {
                foreach($categories as $key_cate=>$cate)
                {
                    if($val == $cate['class_id'])
                    {
                        $cate_names .= $cate['class_name'] . ", ";
                        break;
                    }
                }
            }
        }

        $areas_2 = array();
        if(isset($search_params['area_id_1']))
        {
            $areas_2 = AreaService::getPrefectureList($search_params['area_id_1']);
        }
        $areas = AreaService::getTopAreaList();


        if($province_id == 0){
            if (isset($search_params['province_id'])) {
                $province_id = $search_params['province_id'];
            }
        }
        $province_name = AreaService::getOneAreaFullName($province_id);
        $search_params['province_id'] = $province_id;

        Session::put("keijibann.list.condition", $search_params);
        $area_name = isset($search_params['area_name_arr']) ? $search_params['area_name_arr'] : '';

        return view('user.keijibann.condition',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
                'title'=>'条件で絞り込む',
                'sub_title'=>'',
                "cate_names"=>$cate_names,
                'province_name' => $province_name,
                'province_id' => $province_id,
                'area_name' => $area_name,
                'search_params'=>$search_params,
                'areas'=>$areas,
                'area2'=>$areas_2,
                'tot' => $cnt
            ]);
    }

    public function postCondition(RecruitConditionRequest $request)
    {
        $search_params = $request->input('search_params');
        //$search_params['area_id_2'] = $request->only(['area_id_2'])['area_id_2'];

        $all_params = $request->all();
        if (isset($all_params['search_params'])) {
            unset($all_params['search_params']);
        }
        if (isset($all_params['_token'])) {
            unset($all_params['_token']);
        }
        $all_params = array_merge($all_params, $search_params);

        Session::put("keijibann.list.condition", $all_params);
        return redirect()->route("keijibann.list");
    }

    public function category(Request $request, $cnt)
    {
        $search_params = array();
        $search_params = Session::get('keijibann.list.condition');

        $categories  = LessonClassService::getAllLessonClasses();

        foreach($categories as $key=>$val)
        {
            $data[0] = $val['class_id'];
            $categories[$key]['recruit_cnt'] = KeijibannService::doSearchRecruit(['category_id' => $data,
                'order' => config('const.recruit_order.fav'),
                'user_id' => 0])
                ->count();
        }
        return view('user.keijibann.category', ['page_id' => 'keijibann',
            'page_id_02' => '',
            'title'=>'カテゴリーを選択',
            'sub_title'=>'（複数可能）',
            'categories'=>$categories,
            'search_params'=>$search_params,
            'tot' => $cnt]);
    }

    public function province(Request $request, $prev_url_id)
    {
        $user_id = 0;
        if (Auth::user()) {
            $user_id = Auth::user()->id;
        }

        $arr_lesson_cnt = AreaService::getRecruitCountListByArea(config('const.area_deep_code.pref'), $user_id);
        $region_prefectures = AreaService::getRegionAndPrefectures();
        $class_id = $request->getSession()->get('class_id');

        return view('user.lesson.province',
            [   'page_id' => 'province',
                'page_id_02' => '',
                'class_id' => $class_id,
                'prev_url_id' => $prev_url_id,
                'arr_lesson_cnt' => $arr_lesson_cnt,
                'region_prefectures' => $region_prefectures
            ]);
    }

    public function provinceModal(Request $request)
    {

        $user_id = 0;
        if (Auth::user()) {
            $user_id = Auth::user()->id;
        }

        $arr_lesson_cnt = AreaService::getRecruitCountListByArea(config('const.area_deep_code.pref'), $user_id);
        $region_prefectures = AreaService::getRegionAndPrefectures();
        $class_id = $request->getSession()->get('class_id');

        return response()->json([
            'result_code' => 'success',
            'province_detail' => view('share._province_modal', [
                'class_id' => $class_id,
                'arr_lesson_cnt' => $arr_lesson_cnt,
                'region_prefectures' => $region_prefectures
            ])->render(),
        ]);
    }

    public function areaModal(Request $request)
    {
        $params = $request->all();
        $province_id = $params['province_id'];
        $data = AreaService::getNewLowerAreaList($province_id, 'area_pref', 3);
        $sel_area_ids = explode(',', $params['area_id_arr']);

        $user_id = 0;
        if (Auth::user()) {
            $user_id = Auth::user()->id;
        }

        foreach($data as $k => $v){
            $data[$k]['lesson_count'] = AreaService::getLessonCountByArea($v['area_id'], $user_id);
            $data[$k]['selected'] = 0;
            if(!empty($sel_area_ids)){
                foreach ($sel_area_ids as $_k => $_v){
                    if($v['area_id'] == $_v){
                        $data[$k]['selected'] = 1;
                    }
                }
            }

        }

        return response()->json([
            'result_code' => 'success',
            'area_detail' => view('share._area_modal', [
                'data' => $data
            ])->render(),
        ]);
    }

    public function postCategory(Request $request)
    {
        $search_params = $request->input('search_params');
        $session_params = Session::get("keijibann.list.condition");

        $tot = $request->only(['tot'])['tot'];

        if(isset($search_params['category_id']))
        {
            $session_params['category_id'] = $search_params['category_id'];
        } else{
            $session_params['category_id'] = array();
        }
        Session::put("keijibann.list.condition", $session_params);
        return redirect()->route('keijibann.condition', ['cnt'=>$tot, 'province_id'=>0]);
    }

    public function postGetArea2(Request $request)
    {
        if ($request->has('area_id')) {
            $area_array = AreaService::getPrefectureList($request->only(['area_id']));
            return response()->json(
                [
                    "result_code" => "success",
                    'areas' => $area_array
                ]);
        }
    }

    public function detail(Request $request, $id)
    {
        if(is_object(Auth::user()))
        {
            $recruit_visit_info = RecruitVisitService::getVisitInfo($id, Auth::user()->id);
            if (!is_object($recruit_visit_info) || count($recruit_visit_info) == 0 ) {
                RecruitVisitService::setVisitRecruit([
                    'user_id' => Auth::user()->id,
                    'recruit_id' => $id
                ]);
            }
        }
        $recruit = KeijibannService::getCruitDetails(config('const.recruit_state.all'), $id)->first();
        $cruitUser = $recruit['cruitUser'];

        KeijibannService::incRecruitView($id);

        $login = 0;
        if(isset(Auth::user()->id))
        {
            $login = Auth::user()->id;
        }

        $self_proposed = 0;
        $prop_info = KeijibannService::isProposed($id, $login);

        if(isset($prop_info))
        {
            $self_proposed = 1;
        }

        if($recruit['rc_user_id'] == $login)
        {
            $self_proposed = 1;
        }

        //age
        $recruit['age'] = CommonService::getAge($cruitUser['user_birthday']);

        //sex
        $recruit["sex"] = CommonService::getSexStr($cruitUser['user_sex']);
        $recruit['date'] = CommonService::getMd($recruit['rc_date']);
        $recruit['start_end_time'] = CommonService::getStartAndEndTime($recruit['rc_start_time'], $recruit['rc_end_time']);
        $recruit['date_limit'] = CommonService::getMDH($recruit['rc_period']);

        $buy_count = LessonService::getBuyScheduleCntByKouhaiId($recruit['rc_user_id']);
        $sell_count = LessonService::getSellScheduleCntBySenpaiId($recruit['rc_user_id']);

        return view('user.keijibann.detail',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
                'title'=>'募集の詳細',
                'sub_title'=>'',
                'data'=>$recruit,
                'buy_count' => $buy_count,
                'sell_count' => $sell_count,
                'self_proposed' => $self_proposed
                ]);
    }
    public function input(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('welcome.back');
        }

        $params = $request->only(['rc_id',
            'prop_money',
            'traffic_fee',
            'start_hour',
            'start_minute',
            'end_hour',
            'end_minute',
            'buy_month',
            'buy_day',
            'buy_hour',
            'buy_minute',
            'message',
            'price_mark',
            'fee_type'
            ]);
        $id = $params['rc_id'];

        $recruit = KeijibannService::getCruitDetails(config('const.recruit_state.all'), $id)->first();
        $cruitUser = $recruit['cruitUser'];

        $fee_type = isset($params['fee_type']) ? $params['fee_type'] : KeijibannService::getPropFeeType($cruitUser['id']);

        $confirmed = UserService::getUserConfirmed($recruit['rc_user_id']);

        //age
        $recruit['age'] = CommonService::getAge($cruitUser['user_birthday']);

        //sex
        $recruit["sex"] = CommonService::getSexStr($cruitUser['user_sex']);

        $recruit['lesson_time'] = CommonService::getTimeUnit($recruit['rc_lesson_period_from']);

        $recruit['start_time'] = CommonService::getHM($recruit['rc_start_time']);
        $recruit['end_time'] = CommonService::getHM($recruit['rc_end_time']);

        $recruit['month'] = date('n', strtotime($recruit['rc_date']));
        $recruit['day'] = date('j', strtotime($recruit['rc_date']));

        $recruit['proposal_period'] = $recruit['rc_period'];

        return view('user.keijibann.input',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
                'title'=>'提案内容の入力',
                'sub_title'=>'',
                'data'=>$recruit,
                'params'=>$params,
                'confirmed' => $confirmed,
                'fee_type' =>$fee_type,
                'fee_type_letter' => $fee_type == config('const.fee_type.c') ? "C" : ($fee_type == config('const.fee_type.b') ? "B" : "A"),
                'minMoney'=>SettingService::getSetting('fee_type_a_amount', 'int'),
                'ratio'=>(($fee_type == config('const.fee_type.c') ? SettingService::getSetting('fee_type_c_percent', 'int') : SettingService::getSetting('fee_type_b_percent', 'int') )) / 100,
            ]);
    }

    public function confirm(ProposalRequest $request)
    {
        $params = $request->only(['rc_id',
            'prop_money',
            'traffic_fee',
            'prop_month',
            'prop_day',
            'start_hour',
            'start_minute',
            'end_hour',
            'end_minute',
            'message',
            'month',
            'day',
            'buy_hour',
            'buy_minute',
            'mode',
            'pro_id',
            'user_id',
            'user_name',
            'user_avatar',
            'price_mark',
            'period_start',
            /*'period_end',*/
            'fee_type'
        ]);

        $params['user_id'] = Auth::user()->id;

        $recruit = KeijibannService::getCruitDetails(config('const.recruit_state.all'), $params['rc_id'])->first();
        $cruitUser = $recruit['cruitUser'];

        return view('user.keijibann.confirm',
            ['data'=>$params,
                'page_id' => 'keijibann',
                'page_id_02' => '',
                'title'=>'提案内容の確認',
                'sub_title'=>'',
                'user_id'=>$cruitUser['id']
                ]);
    }

    public function confCom(Request $request)
    {
        $params = $request->only([
            'prop_money',
            'traffic_fee',
            'start_hour',
            'start_minute',
            'end_hour',
            'end_minute',
            'buy_month',
            'buy_day',
            'buy_hour',
            'buy_minute',
            'rc_id',
            'message',
            'mode',
            'user_id',
            'user_name',
            'user_avatar',
            'price_mark',
            'period_start',
            /*'period_end',*/
            'fee_type'
        ]);

        KeijibannService::doCreateProposal($params);
        MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $params['user_id'], "proposal_buy");

        return view('user.keijibann.conf_com',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
                'params'=>$params
                ]);
    }

    public function recruiting(Request $request, $mode)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('welcome.back');
        }
        $params = $request->all();

        $login = -1;
        if(isset(Auth::user()->id))
        {
            $login = Auth::user()->id;
        }

        $recruits_class = KeijibannService::getCruitDetails(config('const.recruit_state.recruiting'), 0, $login, true);
        $recruits = $recruits_class->paginate($this->per_page);
        $pages = $recruits->lastPage();

        $result = array();
        foreach($recruits as $key=>$val)
        {
            $result[$key] = $val;
            $result[$key]['date'] = CommonService::getMd($val['rc_date']);
            $result[$key]['start_end_time'] = CommonService::getStartAndEndTime($val['rc_start_time'], $val['rc_end_time']);
            $result[$key]['fav_count'] = count(FavouriteService::getFavouriteRecruit($val['rc_id']));
            $result[$key]['pro_count'] = KeijibannService::getCountProposals($val['rc_id']);
            $result[$key]['time_diff'] = TimeDisplayService::timeDiffFromDatetime($val['created_at']);
            $result[$key]['lesson_time'] = CommonService::getTimeUnit($val['rc_lesson_period_from']);
            $result[$key]['rc_wish_minmoney'] = CommonService::showFormatNum($val['rc_wish_minmoney']);
            $result[$key]['rc_wish_maxmoney'] = CommonService::showFormatNum($val['rc_wish_maxmoney']);
        }

        if($mode == 2)       //ajax
        {
            return response()->json(
                [
                    "result_code" => "success",
                    'recruits' => $result
                ]);
        }

        $var_params = ['page_id' => 'keijibann',
            'page_id_02' => '',
            'title'=>'掲示板',
            'sub_title'=>'',
            'recruits'=>$result,
            'pages'=>$pages];
        if (isset($params['page_from'])) {
            $var_params['page_from'] = $params['page_from'];
        }
        return view('user.keijibann.recruiting', $var_params);
    }

    public function draft(Request $request, $mode)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('welcome.back');
        }


        $recruits_class = KeijibannService::getCruitDetails(config('const.recruit_state.draft'), 0, 0, true);
        $recruits = $recruits_class->paginate($this->per_page);
        $pages = $recruits->lastPage();

        $result = array();
        foreach($recruits as $key=>$val)
        {
            $result[$key] = $val;
            $result[$key]['date'] = CommonService::getMd($val['rc_date']);
            $result[$key]['start_end_time'] = CommonService::getStartAndEndTime($val['rc_start_time'], $val['rc_end_time']);
            $result[$key]['fav_count'] = count(FavouriteService::getFavouriteRecruit($val['rc_id']));
            $result[$key]['pro_count'] = KeijibannService::getCountProposals($val['rc_id']);
            $result[$key]['time_diff'] = TimeDisplayService::timeDiffFromDatetime($val['created_at']);
            $result[$key]['lesson_time'] = CommonService::getTimeUnit($val['rc_lesson_period_from']);
            $result[$key]['rc_wish_minmoney'] = CommonService::showFormatNum($val['rc_wish_minmoney']);
            $result[$key]['rc_wish_maxmoney'] = CommonService::showFormatNum($val['rc_wish_maxmoney']);
        }
        if($mode == 2)       //ajax
        {
            return response()->json(
                [
                    "result_code" => "success",
                    'recruits' => $result
                ]);
        }

        return view('user.keijibann.draft',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
                'title'=>'掲示板',
                'sub_title'=>'',
                'recruits'=>$result,
                'pages'=>$pages]);
    }

    public function pastContrib(Request $request, $mode)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('welcome.back');
        }

        $recruits_class = KeijibannService::getCruitDetails(config('const.recruit_state.past'));
        $recruits = $recruits_class->paginate($this->per_page);
        $pages = $recruits->lastPage();

        $result = array();
        foreach($recruits as $key=>$val)
        {
            $result[$key] = $val;
            $result[$key]['date'] = CommonService::getMd($val['rc_date']);
            $result[$key]['start_end_time'] = CommonService::getStartAndEndTime($val['rc_start_time'], $val['rc_end_time']);
            $result[$key]['fav_count'] = count(FavouriteService::getFavouriteRecruit($val['rc_id']));
            $result[$key]['time_diff'] = TimeDisplayService::timeDiffFromDatetime($val['created_at']);
            $result[$key]['lesson_time'] = CommonService::getTimeUnit($val['rc_lesson_period_from']);

            $result[$key]['rc_wish_minmoney'] = CommonService::showFormatNum($val['rc_wish_minmoney']);
            $result[$key]['rc_wish_maxmoney'] = CommonService::showFormatNum($val['rc_wish_maxmoney']);
        }
        if($mode == 2)       //ajax
        {
            return response()->json(
                [
                    "result_code" => "success",
                    'recruits' => $result
                ]);
        }

        return view('user.keijibann.past_contrib',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
                'title'=>'掲示板',
                'sub_title'=>'',
                'recruits'=>$result,
                'pages'=>$pages]);
    }

    public function recruitingDetail(Request $request, $id)
    {
        $recruits_class = KeijibannService::getCruitDetails(config('const.recruit_state.all'), $id);
        $recruit = $recruits_class->first();

        $recruit['date'] = CommonService::getMd($recruit['rc_date']);

        $recruit['start_end_time'] = CommonService::getStartAndEndTime($recruit['rc_start_time'], $recruit['rc_end_time']);

        $recruit['fav_count'] = count(FavouriteService::getFavouriteRecruit($recruit['rc_id']));

        $recruit['pro_count'] = KeijibannService::getCountProposals($recruit['rc_id']);

        $recruit['time_diff'] = TimeDisplayService::timeDiffFromDatetime($recruit['created_at']);

        $recruit['lesson_time'] = CommonService::getTimeUnit($recruit['rc_lesson_period_from']);

        $proposals = ProposalService::getPropsFrRecruit($recruit['rc_id']);

        foreach($proposals as $key=>$val)
        {
            $proposals[$key]['age'] = CommonService::getAge($val['proposalUser']['user_birthday']);

            $proposals[$key]["sex"] = CommonService::getSexStr($val['proposalUser']['user_sex']);
            $proposals[$key]["date"] = TimeDisplayService::getDateFromDatetime($val['pro_buy_datetime']);
        }

        return view('user.keijibann.recruiting_detail',
            ['page_id' => 'keijibann',
             'page_id_02' => '',
             'title'=>'募集内容の編集・提案一覧',
             'sub_title'=>'',
             'recruit'=>$recruit,
             'proposals' => $proposals,
             'total'=>count($proposals)
            ]);
    }

    public function recruitingConf(Request $request, $id)
    {
        $proposal = KeijibannService::getProposalById($id)->toArray();
        $recruit_id = $proposal['pro_rc_id'];
        $recruit = KeijibannService::getCruitDetails(config('const.recruit_state.all'), $recruit_id)->first();

        return view('user.keijibann.recruiting_conf',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
                'title'=>'予約内容の確認',
                'sub_title'=>'',
                'prop_id' => $id,
                'proposal'=>$proposal,
                'recruit' => $recruit
            ]);
    }

    public function recruitBookComp(Request $request, $id)
    {
        KeijibannService::updatePropState($id, config('const.prop_state.complete'));
        return view('user.keijibann.recruit_book_comp',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
                'title'=>'',
                'sub_title'=>'',
                'prop_id' => $id
                ]);
    }

    public function recruitingInput(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('welcome.back');
        }

        $lesson_classes = LessonClassService::getAllLessonClasses();
        return view('user.keijibann.recruiting_input',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
                'lesson_classes' => $lesson_classes,
                'mode' => 'input']);
    }

    public function recruitingComp(Request $request)
    {
        return view('user.keijibann.recruiting_comp', ['page_id' => 'keijibann', 'page_id_02' => '']);
    }

    public function recruitingEdit(Request $request, $id)
    {
        $lesson_classes = LessonClassService::getAllLessonClasses();
        $recruit = KeijibannService::getCruitDetails(config('const.recruit_state.all'), $id)->first();
        $lesson_class = $recruit['rc_class_id'];

        $image = '';
        foreach($lesson_classes as $key=>$class)
        {
            if($class['class_id'] == $lesson_class)
            {
                $image = asset('storage/class_image') . '/' . $class['class_image'];
            }
        }

        $title = $recruit['rc_title'];
        $date = $recruit['rc_date'];
        $date_array = preg_split("[:]", $recruit['rc_start_time']) ;
        $start_hour = $date_array[0];
        $start_minute = $date_array[1];
        $date_array = preg_split("[:]", $recruit['rc_end_time']) ;
        $end_hour = $date_array[0];
        $end_minute = $date_array[1];
        $period_start = $recruit['rc_lesson_period_from'];
        $period_end = $recruit['rc_lesson_period_to'];
        $count_man = $recruit['rc_man_num'];
        $count_woman = $recruit['rc_woman_num'];
        $money_start = $recruit['rc_wish_minmoney'];
        $money_end = $recruit['rc_wish_maxmoney'];
        $place = $recruit['rc_place'];
        $place_detail = $recruit['rc_place_detail'];
        $recruit_detail = $recruit['rc_detail'];
        $sex_hope = $recruit['rc_req_sex'];
        $age_start = $recruit['rc_req_age_from'];
        $age_end = $recruit['rc_req_age_to'];
        $recruit_date = $recruit['rc_period'];
        $recruit_id = $recruit['rc_id'];

        return view('user.keijibann.recruiting_input',
            ['page_id' => 'keijibann',
            'page_id_02' => '',
//            'title'=>'募集内容の入力',
            'sub_title'=>'',
            'lesson_classes' => $lesson_classes,
            'lesson_class'=>$lesson_class,
            'title'=>$title,
            'date'=>$date,
            'start_hour'=>$start_hour,
            'start_minute'=>$start_minute,
            'end_hour'=>$end_hour,
            'end_minute'=>$end_minute,
            'period_start'=>$period_start,
            'period_end'=>$period_end,
            'count_man'=>$count_man,
            'count_woman'=>$count_woman,
            'money_start'=>$money_start,
            'money_end'=>$money_end,
            'place'=>$place,
            'place_detail'=>$place_detail,
            'recruit_detail'=>$recruit_detail,
            'sex_hope'=>$sex_hope,
            'age_start'=>$age_start,
            'age_end'=>$age_end,
            'recruit_date'=>$recruit_date,
            'mode'=>'edit',
            'recruit_id' => $recruit_id,
            'class_icon' => $image,
            'recruit' => $recruit,
            ]);
    }

    public function recruitingEditComp(Request $request)
    {
        return view('user.keijibann.recruiting_edit_comp', ['page_id' => 'keijibann', 'page_id_02' => '']);
    }

    public function recruitingDelComp(Request $request)
    {
        return view('user.keijibann.recruiting_del_comp', ['page_id' => 'keijibann', 'page_id_02' => '']);
    }

    public function recruitingContent(Request $request, $id)
    {
        $lesson_classes = LessonClassService::getAllLessonClasses();
        $recruit = KeijibannService::getCruitDetails(config('const.recruit_state.all'), $id)->first();
        $lesson_class = $recruit['rc_class_id'];

        $image = '';
        foreach($lesson_classes as $key=>$class)
        {
            if($class['class_id'] == $lesson_class)
            {

            }
        }

        // $image = CommonService::getLessonClassIconUrl($class['class_icon']);
        $image = CommonService::getLessonClassIconUrl($recruit['cruitLesson']['class_icon']);
        $title = $recruit['rc_title'];
        $date = $recruit['rc_date'];
        $date_array = preg_split("[:]", $recruit['rc_start_time']) ;
        $start_hour = $date_array[0];
        $start_minute = $date_array[1];
        $date_array = preg_split("[:]", $recruit['rc_end_time']) ;
        $end_hour = $date_array[0];
        $end_minute = $date_array[1];
        $period_start = $recruit['rc_lesson_period_from'];
        $period_end = $recruit['rc_lesson_period_to'];
        $count_man = $recruit['rc_man_num'];
        $count_woman = $recruit['rc_woman_num'];
        $money_start = $recruit['rc_wish_minmoney'];
        $money_end = $recruit['rc_wish_maxmoney'];
        $place = implode('/', $recruit->recruit_area_names);
        $place_detail = $recruit['rc_place_detail'];
        $recruit_detail = $recruit['rc_detail'];
        $sex_hope = $recruit['rc_req_sex'];
        $age_start = $recruit['rc_req_age_from'];
        $age_end = $recruit['rc_req_age_to'];
        $recruit_date = $recruit['rc_period'];
        $recruit_id = $recruit['rc_id'];
        $proposal_senpai = $recruit->proposed_senpai;
        $obj_recruit = $recruit;

        return view('user.keijibann.recruiting_content',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
//            'title'=>'募集内容の入力',
                'sub_title'=>'',
                'lesson_classes' => $lesson_classes,
                'lesson_class'=>$lesson_class,
                'title'=>$title,
                'date'=>$date,
                'start_hour'=>$start_hour,
                'start_minute'=>$start_minute,
                'end_hour'=>$end_hour,
                'end_minute'=>$end_minute,
                'period_start'=>$period_start,
                'period_end'=>$period_end,
                'count_man'=>$count_man,
                'count_woman'=>$count_woman,
                'money_start'=>$money_start,
                'money_end'=>$money_end,
                'place'=>$place,
                'place_detail'=>$place_detail,
                'recruit_detail'=>$recruit_detail,
                'sex_hope'=>$sex_hope,
                'age_start'=>$age_start,
                'age_end'=>$age_end,
                'recruit_date'=>$recruit_date,
                'mode'=>'edit',
                'recruit_id' => $recruit_id,
                'proposal_senpai' => $proposal_senpai,
                'obj_recruit' => $obj_recruit,
                'class_icon' => $image]);
    }

    public function recruitingProposal(Request $request, $mode)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('welcome.back');
        }

        $params = $request->all();

        $user_id = Auth::user()->id;

        $proposal_class = KeijibannService::getProposalByUser($user_id);
        $proposals = $proposal_class->paginate($this->per_page);
        $pages = $proposals->lastPage();

        $state_str = array(
            config('const.prop_state.request') => '申請中',
            config('const.prop_state.proposing') => '提案中',
            config('const.prop_state.complete') => '予約成立',
        );

        foreach($proposals as $key=>$val)
        {
            $recruit_id = $val['pro_rc_id'];
            $recruit = KeijibannService::getCruitDetails(config('const.recruit_state.all'), $recruit_id)->first();
            $cruitUser = $recruit['cruitUser'];

            $recruit['date'] = CommonService::getMd($recruit['rc_date']);

            $recruit['lesson_time'] = CommonService::getTimeUnit($recruit['rc_lesson_period_from']);

            //age
            $recruit['age'] =  CommonService::getAge($cruitUser['user_birthday']);

            //sex
            $recruit["sex"] = CommonService::getSexStr($cruitUser['user_sex']);
            $proposals[$key]['recruit'] = $recruit;

            $proposals[$key]['pro_date'] = CommonService::getMDH($val['pro_buy_datetime']);
            $proposals[$key]['pro_time'] = CommonService::getHM($val['pro_buy_datetime']);

            $proposals[$key]['pro_state_str'] = $state_str[$val['pro_state']];
        }

        if($mode == 2)      //ajax
        {
            return response()->json(
                [
                    "result_code" => "success",
                    'proposals' => $proposals
                ]);
        }

        $var_params = ['page_id' => 'keijibann',
            'page_id_02' => '',
            'title'=>'掲示板',
            'sub_title'=>'',
            'pages' => $pages,
            'proposals' => $proposals];
        if (isset($params['page_from'])) {
            $var_params['page_from'] = $params['page_from'];
        }

        return view('user.keijibann.recruiting_proposal', $var_params);
    }

    public function recruitingPropDetail(Request $request, $id)
    {
        $proposal = KeijibannService::getProposalById($id)->toArray();
        $recruit_id = $proposal['pro_rc_id'];
        $recruit = KeijibannService::getCruitDetails(config('const.recruit_state.all'), $recruit_id)->first()->toArray();

        $cruitUser = $recruit['cruit_user'];

        //
        $buy_count = LessonService::getBuyScheduleCntByKouhaiId($recruit['rc_user_id']);
        $sell_count = LessonService::getSellScheduleCntBySenpaiId($recruit['rc_user_id']);

        $recruit['date'] = CommonService::getMd($recruit['rc_date']);

        $recruit['lesson_time'] = CommonService::getTimeUnit($recruit['rc_lesson_period_from']);

        $recruit['start_end_time'] = CommonService::getStartAndEndTime($recruit['rc_start_time'], $recruit['rc_end_time']);

        //age
        $recruit['age'] = CommonService::getAge($cruitUser['user_birthday']);

        //sex
        $recruit["sex"] = CommonService::getSexStr($cruitUser['user_sex']);

        $proposal['pro_date'] = CommonService::getMDH($recruit['rc_period']);

        $proposal['pro_month'] = date('n', strtotime($proposal['pro_buy_datetime']));
        $proposal['pro_day'] = date('j', strtotime($proposal['pro_buy_datetime']));

        $proposal['pro_hour'] = date('H', strtotime($proposal['pro_buy_datetime']));
        $proposal['pro_minute'] = date('i', strtotime($proposal['pro_buy_datetime']));

        $fee_type_letter = ['A', 'B', 'C'];
        return view('user.keijibann.recruiting_prop_detail', [
            'page_id' => 'keijibann',
            'page_id_02' => '',
            'title'=>'募集・提案の詳細',
            'sub_title'=>'',
            'proposal' => $proposal,
            'origin_proposal' => KeijibannService::getProposalById($id),
            'recruit' => $recruit,
            'buy_count' => $buy_count,
            'sell_count' => $sell_count,
            'fee_type_letter'=>$fee_type_letter,
        ]);
    }

    public function recruitingPropEdit(Request $request, $id)
    {
        $proposal = KeijibannService::getProposalById($id)->toArray();

        $rc_id = $proposal['pro_rc_id'];
        $recruit = KeijibannService::getCruitDetails(config('const.recruit_state.all'), $rc_id)->first();
        $recruit_user = $recruit['cruitUser'];
        $confirmed = UserService::getUserConfirmed($recruit['rc_user_id']);

        $recruit['sex'] = CommonService::getSexStr($recruit_user['user_sex']);
        $recruit['age'] = CommonService::getAge($recruit_user['user_birthday']);

        $recruit['month_limit'] = date('n', strtotime($recruit['rc_period']));
        $recruit['day_limit'] = date('j', strtotime($recruit['rc_period']));

        $recruit['month'] = date('n', strtotime($recruit['rc_date']));
        $recruit['day'] = date('j', strtotime($recruit['rc_date']));

        $recruit['start_time'] = CommonService::getHM($recruit['rc_start_time']);
        $recruit['end_time'] = CommonService::getHM($recruit['rc_end_time']);

//proposal
        $proposal['start_hour'] = date('H', strtotime($proposal['pro_start_time']));
        $proposal['start_minute'] = date('i', strtotime( $proposal['pro_start_time']));

        $proposal['end_hour'] = date('H', strtotime( $proposal['pro_end_time']));
        $proposal['end_minute'] = date('i', strtotime( $proposal['pro_end_time']));

        $proposal['pro_month'] = date('n', strtotime( $proposal['pro_buy_datetime']));
        $proposal['pro_day'] = date('j', strtotime( $proposal['pro_buy_datetime']));
        $proposal['pro_hour'] = date('G', strtotime( $proposal['pro_buy_datetime']));
        $proposal['pro_minute'] = date('i', strtotime( $proposal['pro_buy_datetime']));

        $fee_type_letter = ['A', 'B', 'C'];

        return view('user.keijibann.input',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
                'title'=>'提案内容の変更',
                'sub_title'=>'',
                'data' => $recruit,
                'proposal' => $proposal,
                'mode' => 'edit',
                'confirmed'=>$confirmed,
                'fee_type_letter'=>$fee_type_letter,
                ]);
    }

    public function recruitingPropDel(Request $request, $id)
    {
        KeijibannService::delProposal($id);
        return view('user.keijibann.recruiting_prop_del', ['page_id' => 'keijibann', 'page_id_02' => '']);
    }

    public function fee(Request $request)
    {
        $min_fee = SettingService::getSetting('fee_type_a_amount', 'int');
        $percent_B = SettingService::getSetting('fee_type_b_percent', 'int');
        $percent_C = SettingService::getSetting('fee_type_c_percent', 'int');
        $days_B = SettingService::getSetting('fee_type_b_days', 'int');

        return view('user.keijibann.fee',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
                'title'=>'販売手数料について',
                'sub_title'=>'',
                'min_fee'=>$min_fee,
                'percent_B'=>$percent_B,
                'percent_C'=>$percent_C,
                'days_B'=>$days_B
            ]);
    }

    public function postRecruitInput(RecruitRequest $request)
    {
        $params = $request->only(['lesson_classes',
            'title',
            'date',
            'start_hour',
            'start_minute',
            'end_hour',
            'end_minute',
            'period_start',
            /*'period_end',*/
            'count_man',
            'count_woman',
            'money_start',
            'money_end',
            'place',
            'lesson_place',
            'longitude',
            'latitude',
            'place_detail',
            'recruit_detail',
            'sex_hope',
            'age_start',
            'age_end',
            'recruit_date',
            'period_hour',
            'state',
            'mode',
            'map_location',
            'recruit_id']);
        $params['user_id'] = Auth::user()->id;

        if (KeijibannService::doCreateRecruit($params))
        {
            //$request->session()->flash('success', 'ユーザーを追加しました。');
        } else
        {
            //$request->session()->flash('error', 'ユーザーを追加しました。');
        }

        $contents = "";
        if($params['mode'] == "delete") {
            $contents = '募集を<br>削除しました';
        } else if($params['mode'] == "input") {
            if($params['state'] == config('const.recruit_state.recruiting')) {
                $contents = '募集を<br>投稿しました';
            } else {
                $contents = '下書きを<br>保存しました';
            }
        } else { //edit
            if($params['state'] == config('const.recruit_state.recruiting')) {
                $contents = '募集内容を<br>変更しました';
            } else {
                $contents = '下書きを<br>保存しました';
            }
        }
        return view('user.layouts.modal_ok',
            ['page_id' => 'keijibann',
                'page_id_02' => '',
                'contents'=>$contents,
                'url'=>'keijibann']);
    }

    public function postClassIcon(Request $request)
    {
        if($request->has('cat_id'))
        {
            $icon_array = LessonClassService::getClass($request->only(['cat_id'])['cat_id']);
            if(!empty($icon_array))
            {
                return response()->json(
                    [
                        "result_code" => "success",
                        'icon_name' => $icon_array['class_image']
                    ]);
            } else
            {
                return response()->json(
                    [
                        "result_code" => "success",
                        'icon_name' => "no_image.png"
                    ]);
            }
        } else {
            return response()->json(
                [
                    "result_code" => "error",
                    'icon_name' => "no_image.png"
                ]);
        }
    }

    public function postRecruitVote(Request $request)
    {
        if($request->has('id'))
        {
            $params = $request->only(['id',
                'bSelected']);
            $params['user_id'] = Auth::user()->id;

            FavouriteService::setFavoriteRecruit($params);

            return response()->json(
                [
                    "result_code" => "success",
                ]);
        }
    }

    public function postSetRecruitOrder(Request $request)
    {
        if($request->has('orderBy'))
        {
            $orderBy = $request->only(['orderBy'])['orderBy'];

            $search_params_cond = Session::get("keijibann.list.condition");
            $search_params_cond['order'] = $orderBy;
            Session::put("keijibann.list.condition", $search_params_cond);
            return response()->json([
                "result_code" => "success"
            ]);
        }
    }

    public function area(Request $request, $province_id)
    {
        // $area_count_arr = AreaService::getRecruitCountListByArea(4);
        $data = AreaService::getNewLowerAreaList($province_id, 'area_pref', 3);
        $search_params_cond = Session::get("keijibann.list.condition");
        $sel_area_ids = isset($search_params_cond['area_id_arr']) ? $search_params_cond['area_id_arr'] : [];

        $user_id = 0;
        if (Auth::user()) {
            $user_id = Auth::user()->id;
        }

        foreach($data as $k => $v){
            $data[$k]['recruit_count'] = AreaService::getRecruitCountByArea($v['area_id'], $user_id);
            $data[$k]['selected'] = 0;
            if(!empty($sel_area_ids)){
                foreach ($sel_area_ids as $_k => $_v){
                    if($v['area_id'] == $_v){
                        $data[$k]['selected'] = 1;
                    }
                }
            }

        }
        return view('user.lesson.area', [
            'page_id' => 'area',
            'page_id_02' => '',
            'page_from' => 'keijibann',
            'data' => $data
        ]);
    }

    public function setArea(Request $request)
    {
        $params = $request->all();
        $keijibann_area_id_arr = array();
        $keijibann_area_name_arr = '';
        $key = 0;

        for($i = 1; $i <= $params['area_count']; $i++){
            if(isset($params['area_'.$i]) && $params['area_'.$i] == 1){
                $keijibann_area_id_arr[$key++] = intval($params['area_'.$i.'_id']);
                $keijibann_area_name_arr = $keijibann_area_name_arr.$params['area_'.$i.'_name'].', ';
            }
        }
        if(!empty($keijibann_area_name_arr)){
            $keijibann_area_name_arr = substr($keijibann_area_name_arr, 0, -2);
        }
        /*$request->session()->forget('keijibann_area_id_arr');
        $request->session()->put('keijibann_area_id_arr', $keijibann_area_id_arr);
        $request->session()->put('keijibann_area_name_arr', $keijibann_area_name_arr);*/

        $search_params = Session::get("keijibann.list.condition");
        // $tot = $request->only(['tot'])['tot'];

        if(isset($search_params['category_id']))
        {
            $search_params['category_id'] = $search_params['category_id'];
        } else{
            $search_params['category_id'] = array();
        }
        $search_params['area_id_arr'] = $keijibann_area_id_arr;
        $search_params['area_name_arr'] = $keijibann_area_name_arr;
        Session::put("keijibann.list.condition", $search_params);
        return redirect()->route('keijibann.condition', ['cnt'=>0, 'province_id'=>0]);
    }

}

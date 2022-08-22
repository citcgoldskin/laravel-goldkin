<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Service\LessonAccessHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use App\Service\CommonService;
use App\Service\LessonService;
use App\Service\LessonClassService;
use App\Service\UserService;
use App\Service\CouponService;
use App\Service\MessageService;
use App\Service\TalkroomService;
use Session;
use Auth;
class HomeController extends Controller
{
    protected $msg_per_page = 6;

    public function __construct()
    {
    }

    public function index(Request $request)
    {
        return view('user.home.welcome',['page_id' => 'home', 'page_id_02' => '']);
    }

    public function index_back(Request $request)
    {
        // redirect to origin page that call on this page
        if ( !$request->session()->has('url.intended') ) {
            redirect()->setIntendedUrl($request->session()->previousUrl());
        }

        return view('user.home.welcome_back', ['page_id' => '', 'page_id_02' => '']);
    }

    public function lesson_area(Request $request)
    {
        $selected_area_id = $request->cookie('area_id');
        if ( isset($selected_area_id) && $selected_area_id != '' ) {
            return redirect('home');
        }

        $area_name = $request->input('area_name', '');
        $area_id = $request->input('area_id', '');
        $province_id = $request->input('province_id', '');
        /*foreach ( \App\Service\AreaService::getTopAreaList() as $key => $value ) {
            if ( isset($request[$value['area_id']]) ) {
                $area_name = $value['area_name'];
                $area_id = $value['area_id'];
                break;
            }
        }*/

        return view('user.home.lesson_area',[
            'area_id' => $area_id,
            'area_name' => $area_name,
            'province_id' => $province_id,
            'page_id' => 'home',
            'page_id_02' => ''
        ]);
    }

    public function select_area(Request $request)
    {
        return view('user.home.select_area',['page_id' => 'home', 'page_id_02' => '']);
    }

    public function home(Request $request, $area_id = '', $province_id = '')
    {
        if ( $area_id != '' ) {
            Cookie::queue(Cookie::make('area_id', $area_id, 1));
        }
        if ( $province_id != '' ) {
            /*Cookie::queue(Cookie::make('search_province_id', $province_id, 1));*/
            $request->session()->put('search_province_id', $province_id);
        }
        if ($request->getSession()->get('search_province_id')) {
            // カテゴリー
            $request->session()->put('province_id', $request->getSession()->get('search_province_id'));
            // 掲示板
            $search_params_cond = Session::get("keijibann.list.condition", []);
            $search_params_cond['province_id'] = $request->getSession()->get('search_province_id');
            $request->session()->put("keijibann.list.condition", $search_params_cond);

            $request->session()->forget('search_province_id');
        }

        $class_list = LessonClassService::getAllLessonClasses();
        $recommend_list= LessonService::getRecommendLessons();
        //$brows_list= Auth::guard('web')->check() ? LessonService::getBrowseLessons(Auth::user()->id) : [];
        $brows_list= Auth::guard('web')->check() ? LessonAccessHistoryService::getLessonAccessHistoryInfo(Auth::user()->id) : [];
        $fav_senpais = UserService::getFavoriteSenpais();
        $reserved_list = LessonService::getReservedLessons();
        return view('user.home.home',
            [
                'page_id' => 'home',
                'page_id_02' => '',
                'class_list'=>$class_list,
                'recommend_list'=>$recommend_list,
                'fav_senpais'=>$fav_senpais,
                'brows_list'=>$brows_list,
                'reserved_list'=>$reserved_list
            ]);
    }

    public function splash(Request $request)
    {
        Session::put('invite_code', $request->get('invite_code'));
        return view('user.home.splash', ['page_id' => '', 'page_id_02' => '']);
    }

    public function showUsingRules(Request $request) {
        return view('user.documents.using_rules', ['page_id' => '', 'page_id_02' => '']);
    }

    public function showPrivacyPolicy(Request $request) {
        return view('user.documents.privacy_policy',['page_id' => '', 'page_id_02' => '']);
    }

    public function notice(Request $request, $mode = '')
    {
        $user_id = Auth::user()->id;
        //notice
        /*$notice_message_obj = MessageService::getMsg($user_id, MessageService::MSG_CLASS_OWN_NEWS);*/
        $notice_message_obj = MessageService::getMsg($user_id, MessageService::MSG_CLASS_OWN_NEWS);
        $notice_count = $notice_message_obj->count();
        $notice_message = $notice_message_obj->paginate($this->msg_per_page);
        $notice_pages = ceil($notice_count / $this->msg_per_page);

        $notice = array();
        foreach ($notice_message as $key => $value){
            $notice[$key]['msg_content'] = $value['msg_content'];
            $notice[$key]['msg_date'] = date('Y/n/j', strtotime($value['created_at']));
            if($value['msg_open'] == MessageService::MSG_STATE_CLOSE){
                MessageService::updateMsgOpen($value['msg_id']);
            }
        }
        if($mode == 'notice_ajax'){
            return response()->json(
                [
                    "result_code" => "success",
                    'message' => $notice
                ]);
        }

        //news
        $news_message_obj = MessageService::getMsg($user_id, MessageService::MSG_CLASS_NEWS);
        $news_count = $news_message_obj->count();
        $news_message = $news_message_obj->paginate($this->msg_per_page);
        $news_pages = ceil($news_count / $this->msg_per_page);

        $news = array();
        foreach ($news_message as $key => $value){
            $news[$key]['msg_content'] = $value['msg_content'];
            $news[$key]['msg_date'] = date('Y/n/j', strtotime($value['created_at']));
            if($value['msg_open'] == MessageService::MSG_STATE_CLOSE){
                MessageService::updateMsgOpen($value['msg_id']);
            }
        }
        if($mode == 'news_ajax'){
            return response()->json(
                [
                    "result_code" => "success",
                    'message' => $news
                ]);
        }
        return view('user.home.notice',
            [
                'page_id' => 'notice',
                'page_id_02' => '',
                'notice' => $notice,
                'news' => $news,
                'notice_pages' => $notice_pages,
                'news_pages' => $news_pages
            ]);
    }

    public function todo(Request $request, $mode = '')
    {
        $user_id = Auth::user()->id;
        $message_obj = MessageService::getMsg($user_id, MessageService::MSG_CLASS_SALE);
        $total_count = $message_obj->count();
        $message = $message_obj->paginate($this->msg_per_page);
        $pages = ceil($total_count / $this->msg_per_page);

        $data = array();
        foreach ($message as $key => $value){
            $data[$key]['msg_content'] = str_replace('{$from_user_name}',$value['user']['name'],$value['msg_tpl']['mt_msg_content']);
            $data[$key]['msg_date'] = date('Y/n/j', strtotime($value['created_at']));
            $data[$key]['user_avatar'] = CommonService::getUserAvatarUrl($value['user']['user_avatar']);
            $data[$key]['user_type'] = $value['msg_tpl']['mt_name'];
            if($value['msg_open'] == MessageService::MSG_STATE_CLOSE){
                MessageService::updateMsgOpen($value['msg_id']);
            }
        }
        if($mode == 'ajax'){
            return response()->json(
                [
                    "result_code" => "success",
                    'message' => $data
                ]);
        }
        return view('user.home.todo',
            [
                'page_id' => 'todo',
                'page_id_02' => '',
                'data' => $data,
                'pages' => $pages
            ]);
    }

    public function getNewMsgCnt(Request $request)
    {
        $result = array();
        if(Auth::guard('web')->check()){
            $user_id = Auth::user()->id;
            $sale_count = MessageService::getNewMsgCnt($user_id, MessageService::MSG_CLASS_SALE);
            $news_count = MessageService::getNewMsgCnt($user_id, MessageService::MSG_CLASS_NEWS);
            $notice_count = MessageService::getNewMsgCnt($user_id, MessageService::MSG_CLASS_NOTICE);
            $notice_count += $news_count;

            $talk_count = TalkroomService::getUnreadMsgCnt($user_id);

            $result['state'] = true;
            $result['sale_cnt'] = $sale_count;
            $result['notice_cnt'] = $notice_count;
            $result['talk_cnt'] = $talk_count;
        }else{
            $result['state'] = false;
        }

        return response()->json($result);
    }

    public function guide(Request $request, $type)
    {
        $user_info = Auth::guard('web')->check() ? Auth::user() : null;
        return view('user.home.menu',
            [
                'page_id' => 'talkroom',
                'page_id_02' => '',
                'user_info' => $user_info,
                'type'=>$type
            ]
        );
    }

}

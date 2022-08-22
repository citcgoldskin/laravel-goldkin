<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BankAccountRequest;
use App\Http\Requests\User\CancelScheduleRequest;
use App\Http\Requests\User\PhoneRequest;
use App\Http\Requests\User\LessonReserveRequest;
use App\Http\Requests\User\TransferApplicationRequest;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\LessonRequestSchedule;
use App\Models\Setting;
use App\Providers\RouteServiceProvider;
use App\Service\BankService;
use App\Service\BlockService;
use App\Service\CommonService;
use App\Service\ContactService;
use App\Service\LessonService;
use App\Service\EvalutionService;
use App\Service\CouponService;
use App\Service\SettingService;
use App\Service\TalkroomService;
use App\Service\PersonConfirmService;
use App\Service\TransferApplicationService;
use FontLib\Table\Type\loca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Question;
use App\Service\UserService;
use App\Service\SenpaiService;
use App\Service\FavouriteService;
use App\Service\QuestionService;
use App\Service\AreaService;
use App\Service\MessageService;
use Square\Models\BankAccount;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Http\Requests\User\SenpaiRequest;
use Auth;
use PDF;

use App\Models\CancelReasonType;

class MyAccountController extends Controller
{
    const GRANT_SENPAI = 1;
    const GRANT_NO_SENPAI = 2;

    protected $per_page = 5;
    protected $page_limit = 20;
    protected $fav_lessons_per_page = 10;
    protected $fav_users_per_page = 20;
    protected $requests_per_page = 5;
    protected $schedules_per_page = 20;

    public function __construct()
    {
        $this->middleware('auth:myaccount')->except([
            'quesCate',
            'quesList',
            'quesCateSmall',
            'quesSearch',
            'quesDetail',
            'others',
            'companyAbstract',
            'personalInfo',
            'saleMethod',
            'profile',
            'payMethod'
            ]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $grant = self::GRANT_NO_SENPAI;
        if ( $user->user_is_senpai ) {
            $grant = self::GRANT_SENPAI;
        }

        return view('user.myaccount.index_logged', [
            'user_info' => $user,
            'grant' => $grant,
            'title' => 'マイページ', 'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function profile(Request $request, $profileUserID)
    {
        $user_info = UserService::getUserByID($profileUserID);
        if (empty($user_info)) {
            return false;
        }
        $user_info['avatar'] = CommonService::getUserAvatarUrl($user_info['user_avatar']);
        $user_info['age'] = CommonService::getAge($user_info['user_birthday']);
        $user_info['sex'] = CommonService::getSexStr($user_info['user_sex']);
        if(!empty($user_info['userConfirm'])){
            $user_info['self_confirm'] = $user_info['userConfirm']['pc_state'];
        }else{
            $user_info['self_confirm'] = config('const.pers_conf.make');
        }
        $user_info['area_name'] = AreaService::getOneAreaFullName($user_info['user_area_id']);
        $user_info['buy_schedule_count'] = LessonService::getBuyScheduleCntByKouhaiId($profileUserID);
        $user_info['sell_schedule_count'] = 0;
        $user_info['senpai_id'] = $profileUserID;
        if($profileUserID > 0){
            $user_info['senpai_id'] = $profileUserID;
            $user_info['sell_schedule_count'] = LessonService::getSellScheduleCntBySenpaiId($profileUserID);
            $user_info['lesson'] = LessonService::getLessonListBySenpai($profileUserID);
            $lesson_images = array();
            foreach ($user_info['lesson'] as $k => $v){
                $first_image = LessonService::getLessonFirstImage($v);

                if(!empty($first_image)){
                    array_push($lesson_images, $first_image);
                }
            }
        }
        if(isset(Auth::user()->id)){
            $self_user_id = Auth::user()->id;
        }else{
            $self_user_id = 0;
        }
        $user_info['self_user_id'] = $self_user_id;
        $user_info['user_id'] = $profileUserID;
        $user_info['favourite'] = FavouriteService::getFavouriteState($self_user_id, $profileUserID);

        return view('user.myaccount.profile', ['page_id' => 'profile', 'page_id_02' => '', 'user_info' => $user_info, 'lesson_images' => $lesson_images]);

    }

    public function ajaxSenpaiFavourite(Request $request)
    {
        $params = $request->only(['senpai_id', 'bSelected']);
        $params['user_id'] = Auth::user()->id;

        FavouriteService::setSenpaiFavourite($params);

        return response()->json(
            [
                "result_code" => "success",
            ]);
    }

    //rcr
    public function showEditProfileForm(Request $request)
    {
        $user = Auth::User();
        if ( is_null($user) ) {
            return redirect()->route('user.login.myaccount.before');
        }

        // get age
        $user['age'] = CommonService::getAge($user['user_birthday']);

        // get area data
        $area = AreaService::getTopAreaList();

        $prefectures = AreaService::getRegionAndPrefectures();

        return view('user.myaccount.edit_profile', [
            'user_info' => $user,
            'prefectures' => $prefectures,
            'title' => 'プロフィール情報編集', 'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function editProfile(Request $request)
    {
        $update_params = $request->only(['name', 'user_intro', 'area_id']);
        //
        $error_messages = array();
        $error_messages['area_id.required'] = "登録地を入力してください。";
        $attributes['name'] = "ニックネーム";
        $attributes['area_id'] = "登録地";
        $validator = validator::make($update_params, [
            'name' => 'required|string|max:50',
            'area_id'=>'required|integer'
        ], $error_messages, $attributes);

        if ( $validator->fails() ) {
            $errors = $validator->errors();
            return response()->json(["result_code" => "failed", "res" => $errors]);
        }

        $download_file = $request->file('user_avatar');
        if ( !is_null($download_file) ) {
            $array_filename = explode('.', $download_file->getClientOriginalName());
            $new_filename = $array_filename[0].'_'.strtotime(now()).'.'. $array_filename[1];
            $path = $request->file('user_avatar')->storeAs('public/avatar', $new_filename);
            if ( $path ) {
                $update_params['user_avatar'] = $new_filename;
            }
        }

        $update_params['user_area_id'] = $update_params['area_id'];

        $res = Auth::User()->update($update_params);

        if ( $res ) {
            return response()->json(["result_code" => "success"], 200);
        } else {
            return response()->json(["result_code" => "failed"]);
        }
    }

    public function edit(Request $request)
    {
        return view('user.myaccount.payment_mgr', ['page_id' => 'payment_mgr', 'page_id_02' => '']);
    }

    public function favorite(Request $request, $type = 0)
    {
        $user_id = Auth::user()->id;

        $fav_lessons = FavouriteService::getFavLessons($user_id)->paginate($this->fav_lessons_per_page, ['*'], 'lesson');
        $fav_follows = FavouriteService::getFavFollows($user_id)->paginate($this->fav_users_per_page, ['*'], 'follow');
        $fav_followers = FavouriteService::getFavFollowers($user_id)->paginate($this->fav_users_per_page, ['*'], 'follower');

        $fav_counts[config('const.favourite_type.lesson')] = $fav_lessons->total();
        $fav_counts[config('const.favourite_type.follow')] = $fav_follows->total();
        $fav_counts[config('const.favourite_type.follower')] = $fav_followers->total();

        if ( !is_null($request['type']) ) {
            $type = $request['type'];
        }

        return view('user.myaccount.favorite', [
            'fav_lessons' => $fav_lessons,
            'fav_follows' => $fav_follows,
            'fav_followers' => $fav_followers,
            'counts' => $fav_counts,
            'type' => $type,
            'title' => 'お気に入り', 'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function requestMgr(Request $request, $type = 0)
    {
        $reserve_order = $request['reserve_order'] ? $request['reserve_order'] : 0;
        $attendance_order = $request['attend_order'] ? $request['attend_order'] : 0;
        $user_id = Auth::user()->id;

        $reserved_request_infos = LessonService::getRequestInfosByKouhaiId($user_id, config('const.request_type.reserve'), $reserve_order)->paginate($this->requests_per_page, ['*'], 'reserve');
        $reserved_req_count = $reserved_request_infos->total();
        $attend_request_infos = LessonService::getRequestInfosByKouhaiId($user_id, config('const.request_type.attend'), $reserve_order)->paginate($this->requests_per_page, ['*'], 'attend');
        $attendance_req_count = $attend_request_infos->total();

        $schedules_grouped_confirm_date = array();
        foreach ( $reserved_request_infos as $value) {
            $until_buys = LessonRequestSchedule::where('lrs_lr_id', $value['lr_id'])
                ->where('lrs_state', config('const.schedule_state.confirm'))
                ->get()
                ->groupBy('lrs_confirm_date');

            $schedules_grouped_confirm_date[$value['lr_id']] = $until_buys;
        }

        foreach ( $attend_request_infos as $value) {
            $until_buys = LessonRequestSchedule::where('lrs_lr_id', $value['lr_id'])
                ->where('lrs_state', config('const.schedule_state.confirm'))
                ->get()
                ->groupBy('lrs_confirm_date');

            $schedules_grouped_confirm_date[$value['lr_id']] = $until_buys;
        }

        if ( !is_null($request['type']) ) {
            $type = $request['type'];
        }

        return view('user.myaccount.request_mgr', [
                'reserved_request_infos' => $reserved_request_infos,
                'attend_request_infos' => $attend_request_infos,
                'schedules_grouped_confirm_date' => $schedules_grouped_confirm_date,
                'reserved_req_count' => $reserved_req_count,
                'attendance_req_count' => $attendance_req_count,
                'type' => $type,
                'reserve_order' => $reserve_order,
                'attendance_order' => $attendance_order,
                'title' => 'リクエスト管理', 'page_id' => 'mypage', 'page_id_02' => ''
        ]);
    }

    public function paymentMgr(Request $request)
    {
        $parmas = $request->all();
        $obj_user = Auth::user();

        // 月売上
        $month_amount = 0;
        // 月出金
        $month_withdrawal = 0;
        $compare_month = Carbon::now()->format('n');
        if (isset($params['_month']) && $params['_month']) {
            $compare_month = $params['_month'];
        }

        // 売上金残高合計
        $remain_all_price = LessonService::getRemainPrice($obj_user->id);
        $next_month_date = Carbon::now()->addMonths(1)->format('Y/m/t');
        $remain_next_month_price = LessonService::getPeriodApplication($obj_user->id, $next_month_date);

        // 振込申請可能な売上 compare current date
        $current_possible_price = LessonService::getPeriodApplication($obj_user->id, Carbon::now()->format('Y-m-d'));

        // XX月XX日以降の申請可能な売上
        $possible_send_date = Carbon::now()->addMonths(1)->format('Y-m-'.str_pad(config('const.transfer_config.possible_send_date'), 2, "0", STR_PAD_LEFT));
        $possible_send_price = LessonService::getPossibleSendPrice($obj_user->id, $possible_send_date);
        $possible_send_date = Carbon::now()->addMonths(1)->format('n月'.config('const.transfer_config.possible_send_date').'日');


        // chart data
        $date_interval = \DateInterval::createFromDateString("1 month");
        $start_date = new \DateTime(Carbon::now()->addMonths(-5)->format('Y-m-d'));
        $end_date = new \DateTime(Carbon::now()->format('Y-m-d'));
        $end_date = $end_date->modify( '+1 day' );
        $period = new \DatePeriod($start_date, $date_interval, $end_date);
        $chat_info = [];
        foreach($period as $dt) {
            $from_date = $dt->format('Y-m-01');
            $to_date = Carbon::parse($dt)->addMonths(1)->format('Y-m-01');
            $month = $dt->format('n');

            $temp = [];
            $temp['date_label'] = $from_date;
            $temp['amount'] = LessonService::getEarningAmount($obj_user->id, config('const.date_type.period'), $from_date, $to_date);
            $temp['withdrawal'] = TransferApplicationService::getTransferAmountByPeriod($obj_user->id, $from_date, $to_date);

            $chat_info[] = $temp;

            if ($compare_month == $month) {
                $month_amount = $temp['amount'];
                $month_withdrawal = $temp['withdrawal'];
            }
        }

        if (isset($params['_month']) && $params['_month']) {

        }
        return view('user.myaccount.payment_mgr', [
            'title' => '売上管理・振込申請',
            'page_id' => 'mypage',
            'page_id_02' => '',
            'chat_info' => $chat_info,
            'month_amount' => $month_amount,
            'month_withdrawal' => $month_withdrawal,
            'remain_all_price' => $remain_all_price,
            'remain_next_month_price' => $remain_next_month_price,
            'next_month_date' => $next_month_date,
            'possible_send_date' => $possible_send_date,
            'possible_send_price' => $possible_send_price,
            'current_possible_price' => $current_possible_price,
            'obj_user' => $obj_user
        ]);
    }

    public function paymentKouhaiDetail(Request $request)
    {
        $parmas = $request->all();
        $lesson_request_schedule_id = $parmas['lrs_id'];
        $obj_lrs = LessonRequestSchedule::find($lesson_request_schedule_id);

        return view('user.myaccount.payment_kouhai_detail', [
            'title' => '後輩情報',
            'page_id' => '',
            'page_id_02' => '',
            'obj_lrs' => $obj_lrs,
            'obj_kouhai' => $obj_lrs->lesson_request->user,
            'obj_lesson' => $obj_lrs->lesson_request->lesson
        ]);
    }

    public function putMoney(Request $request)
    {
        $obj_user = Auth::user();
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $application_send_date = Carbon::now()->addMonths(1)->format('Y-m-'.str_pad(config('const.transfer_config.application_send_date'), 2, '0', STR_PAD_LEFT));
        $application_limit_date = Carbon::now()->format('Y-m-'.str_pad(config('const.transfer_config.application_end_date'), 2, '0', STR_PAD_LEFT).' 23:59');
        if ($now > $application_limit_date) {
            $application_send_date = Carbon::parse($application_send_date)->addMonths(1)->format('Y-m-d');
            $application_limit_date = Carbon::parse($application_limit_date)->addMonths(1)->format('Y-m-d H:i');
        }
        $application_send_date = Carbon::parse($application_send_date)->format('n月j日');
        $application_limit_date = Carbon::parse($application_limit_date)->format('n月j日 H時i分');

        return view('user.myaccount.put_money', [
            'title' => '振込申請',
            'page_id' => '',
            'obj_user' => $obj_user,
            'application_send_date' => $application_send_date,
            'application_limit_date' => $application_limit_date,
            'page_id_02' => ''
        ]);
    }

    public function applyTransferMoney(TransferApplicationRequest $request)
    {
        $params = $request->all();
        $obj_user = Auth::user();
        $condition = [
            'ta_user_id' => $obj_user->id,
            'ta_send_total_price' => $params['transfer_all_price'],
            'ta_fee' => $params['transfer_fee'],
            'ta_profit_price' => $params['transfer_profit_price'],
        ];
        if (TransferApplicationService::doApplication($condition, $obj_user)) {
            $request->session()->flash('success', '振込申請をしました。');
            return redirect()->route('user.myaccount.payment_mgr');
        } else {
            $request->session()->flash('error', '振込申請が失敗しました。');
            return back();
        }
    }

    public function putMoneyTerm(Request $request)
    {
        $obj_user = Auth::user();
        $now = Carbon::now()->format('Y-m-d');

        $date_interval = \DateInterval::createFromDateString("1 month");
        $start_date = new \DateTime(Carbon::now()->format('Y-m-d'));
        $end_date = new \DateTime(Carbon::now()->addMonths(6)->format('Y-m-d'));
        //$end_date = $end_date->modify( '+1 day' );
        $period = new \DatePeriod($start_date, $date_interval, $end_date);
        $period_application = [];
        foreach($period as $dt) {
            $date_key = Carbon::parse($dt)->format('Y/m/t');
            $period_application[$date_key] = LessonService::getPeriodApplication($obj_user->id, $date_key);
        }
        return view('user.myaccount.put_money_term', [
            'title' => '売上金の振込申請期限',
            'page_id' => 'mypage',
            'obj_user' => $obj_user,
            'period_application' => $period_application,
            'page_id_02' => ''
        ]);
    }

    public function paymentDetail(Request $request)
    {
        return view('user.myaccount.payment_detail', ['title' => '売上の振込申請の詳細について', 'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function masterLessonHistroy(Request $request)
    {
        $state = config('const.schedule_state.reserve');
        if ( !is_null($request['state']) ) {
            $state = $request['state'];
        }

        // set keyword
        $keyword = array();
        if ( !is_null($request['keyword_reserve']) ) {
            $keyword[config('const.schedule_state.reserve')] = $request['keyword_reserve'];
        } else {
            $keyword[config('const.schedule_state.reserve')] = '';
        }

        if ( !is_null($request['keyword_complete']) ) {
            $keyword[config('const.schedule_state.complete')] = $request['keyword_complete'];
        } else {
            $keyword[config('const.schedule_state.complete')] = '';
        }

        if ( !is_null($request['keyword_cancel']) ) {
            $keyword[config('const.schedule_state.cancel_senpai')] = $request['keyword_cancel'];
        } else {
            $keyword[config('const.schedule_state.cancel_senpai')] = '';
        }

        // set order
        $order = array();
        if ( !is_null($request['order_reserve']) ) {
            $order[config('const.schedule_state.reserve')] = $request['order_reserve'];
        } else {
            $order[config('const.schedule_state.reserve')] = '';
        }

        if ( !is_null($request['order_complete']) ) {
            $order[config('const.schedule_state.complete')] = $request['order_complete'];
        } else {
            $order[config('const.schedule_state.complete')] = '';
        }

        if ( !is_null($request['order_cancel']) ) {
            $order[config('const.schedule_state.cancel_senpai')] = $request['order_cancel'];
        } else {
            $order[config('const.schedule_state.cancel_senpai')] = '';
        }

        // search by term
        $start_date[config('const.schedule_state.complete')] = NULL;
        $end_date[config('const.schedule_state.complete')] = NULL;

        if ( !is_null($request['order_complete']) && $request['order_complete'] == 1 ) {
            if ( !is_null($request['from_complete']) ) {
                $start_date[config('const.schedule_state.complete')] = $request['from_complete'];
            }

            if ( !is_null($request['to_complete']) ) {
                $end_date[config('const.schedule_state.complete')] = $request['to_complete'];
            }
        }


        $start_date[config('const.schedule_state.cancel_senpai')] = NULL;
        $end_date[config('const.schedule_state.cancel_senpai')] = NULL;

        if ( !is_null($request['from_cancel']) ) {
            $start_date[config('const.schedule_state.cancel_senpai')] = $request['from_cancel'];
        }

        if ( !is_null($request['to_cancel']) ) {
            $end_date[config('const.schedule_state.cancel_senpai')] = $request['to_cancel'];
        }

        $senpai_id = Auth::user()->id;

        // get lists
        $schedule_lists[config('const.schedule_state.reserve')] = LessonService::getScheduleListBySenpaiId(
            $senpai_id,
            config('const.schedule_state.reserve'),
            $keyword[config('const.schedule_state.reserve')],
            $order[config('const.schedule_state.reserve')]
            )->paginate($this->schedules_per_page, ['*'], 'reserve');
        $counts[config('const.schedule_state.reserve')] = $schedule_lists[config('const.schedule_state.reserve')]->total();

        $schedule_lists[config('const.schedule_state.complete')] = LessonService::getScheduleListBySenpaiId(
            $senpai_id,
            config('const.schedule_state.complete'),
            $keyword[config('const.schedule_state.complete')],
            $order[config('const.schedule_state.complete')],
            $start_date[config('const.schedule_state.complete')],
            $end_date[config('const.schedule_state.complete')]
        )->paginate($this->schedules_per_page, ['*'], 'complete');
        $counts[config('const.schedule_state.complete')] = $schedule_lists[config('const.schedule_state.complete')]->total();

        $schedule_lists[config('const.schedule_state.cancel_senpai')] = LessonService::getScheduleListBySenpaiId(
            $senpai_id,
            config('const.schedule_state.cancel_senpai'),
            $keyword[config('const.schedule_state.cancel_senpai')],
            $order[config('const.schedule_state.cancel_senpai')],
            $start_date[config('const.schedule_state.cancel_senpai')],
            $end_date[config('const.schedule_state.cancel_senpai')]
        )->paginate($this->schedules_per_page, ['*'], 'cancel');
        $counts[config('const.schedule_state.cancel_senpai')] = $schedule_lists[config('const.schedule_state.cancel_senpai')]->total();

        return view('user.myaccount.master_lesson_history', [
            'schedule_lists' => $schedule_lists,
            'counts' => $counts,
            'state' => $state,
            'keyword' => $keyword,
            'order' => $order,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'title' => 'レッスン履歴', 'page_id' => 'mypage', 'page_id_02' => '']);
    }

    //public function receipt(Request $request, OrderLesson $order_lesson)
    public function receipt(Request $request)
    {
        $pdf = PDF::loadView('export.pdf.issue_receipt_pdf', [
            'order_lesson'=>null,
            'obj_user'=>Auth::user(),
        ]);

        return $pdf->download('領収書_'. \Carbon\Carbon::now()->format('YmdHis').'.pdf');
        //return view('user.orders.receipt', []);
    }

    public function masterLessonRequest(Request $request, $schedule_id)
    {
        $schedule_info = LessonService::getScheduleInfoById($schedule_id);
        if ( is_null($schedule_info) ) {
            return redirect()->route('user.myaccount.master_lesson_history');
        }

        // user validation
        $senpai_id = Auth::user()->id;
        if ( $schedule_info['lesson_request']['lesson']['lesson_senpai_id'] != $senpai_id ) {
            return redirect()->route('user.myaccount.master_lesson_history');
        }

        $state = $schedule_info['lrs_state'];

        // coupon
        $schedule_info['coupon'] = CouponService::getCouponValue($schedule_info['lrs_id']);

        $title = '';
        if ( $state == config('const.schedule_state.reserve') ) {
            $title = '予約中のレッスン';
        } elseif ( $state == config('const.schedule_state.complete') ) {
            $title = '完了したレッスン';
        } elseif ( $state >= config('const.schedule_state.cancel_senpai') ) {
            $title = 'キャンセル';
        } else {
            return redirect()->route('user.myaccount.master_lesson_history');
        }

        return view('user.myaccount.master_lesson_request', [
            'state' => $state,
            'schedule_info' => $schedule_info,
            'title' => $title,
            'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function cancelMsterLsn(Request $request, $schedule_id)
    {
        $schedule_info = LessonService::getScheduleInfoById($schedule_id);
        if ( is_null($schedule_info) ) {
            return redirect()->route('user.myaccount.master_lesson_history');
        }

        // user validation
        $senpai_id = Auth::user()->id;
        if ( $schedule_info['lesson_request']['lesson']['lesson_senpai_id'] != $senpai_id ) {
            return redirect()->route('user.myaccount.master_lesson_history');
        }

        $cancel_reason_types = CommonService::getCancelReasonTypes(config('const.cancel_kind.senpai_cancel'));

        return view('user.myaccount.cancel_lesson', [
            'schedule_info' => $schedule_info,
            'cancel_reason_types' => $cancel_reason_types,
            'title' => 'レッスンのキャンセル', 'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function cancelMsterLsnSuccess(CancelScheduleRequest $request)
    {
        // schedule_id validation
        $schedule_info = LessonService::getScheduleInfoById($request['schedule_id']);
        if ( is_null($schedule_info) ) {
            return redirect()->route('user.myaccount.master_lesson_history');
        }

        // user validation
        $senpai_id = Auth::user()->id;
        if ( $schedule_info['lesson_request']['lesson']['lesson_senpai_id'] != $senpai_id ) {
            return redirect()->route('user.myaccount.master_lesson_history');
        }

        $res = LessonService::cancelSchedule($request['schedule_id'], $request['commitment'], config('const.user_type.cancel_senpai'), $request['other_reason']);
        if ( !$res ) {
            return redirect(route('user.myaccount.cancel_lesson', ['schedule_id' => $request['schedule_id']]));
        }

        // send talkroom message
        $kouhai_id = $schedule_info['lesson_request']['lr_user_id'];
        // get cancel reasons
        $reasons = array();
        $obj_reasons = CancelReasonType::whereIn('crt_id', $request['commitment'])->get();
        foreach ($obj_reasons as $obj_reason) {
            if ( $obj_reason['crt_id'] == config('const.senpai_cancel_other_reason_id') ||
                $obj_reason['crt_id'] == config('const.kouhai_cancel_other_reason_id') ) {
                $reasons[] = $request['other_reason'];
                continue;
            }
            $reasons[] = $obj_reason['crt_content'];
        }

        $ls_title = $schedule_info['lesson_request']['lesson']['lesson_title'];
        $start_date = CommonService::getYMD($schedule_info['lrs_date']) . ' ' .
                        date('H:i') . '~';
        $ls_img = CommonService::unserializeData($schedule_info['lesson_request']['lesson']['lesson_image'])[0];

        TalkroomService::saveKouhaiRequestSenpaiCancel($kouhai_id, $senpai_id, $reasons, $ls_title, $start_date, $ls_img);
        TalkroomService::saveSenpaiRequestCancel($senpai_id, $kouhai_id, $reasons, $ls_title, $start_date, $ls_img );

        // send todo_list message
        MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $kouhai_id, "reserve_cancel");

        return view('user.myaccount.cancel_lesson_success', [
            'schedule_info' => $schedule_info,
            'title' => '', 'page_id' => 'mypage', 'page_id_02' => '']);

    }

    public function studentLessonHistory(Request $request)
    {
        $state = config('const.schedule_state.reserve');
        if ( !is_null($request['state']) ) {
            $state = $request['state'];
        }

        // set keyword
        $keyword = array();
        if ( !is_null($request['keyword_reserve']) ) {
            $keyword[config('const.schedule_state.reserve')] = $request['keyword_reserve'];
        } else {
            $keyword[config('const.schedule_state.reserve')] = '';
        }

        if ( !is_null($request['keyword_complete']) ) {
            $keyword[config('const.schedule_state.complete')] = $request['keyword_complete'];
        } else {
            $keyword[config('const.schedule_state.complete')] = '';
        }

        if ( !is_null($request['keyword_cancel']) ) {
            $keyword[config('const.schedule_state.cancel_senpai')] = $request['keyword_cancel'];
        } else {
            $keyword[config('const.schedule_state.cancel_senpai')] = '';
        }

        // set order
        $order = array();
        if ( !is_null($request['order_reserve']) ) {
            $order[config('const.schedule_state.reserve')] = $request['order_reserve'];
        } else {
            $order[config('const.schedule_state.reserve')] = '';
        }

        if ( !is_null($request['order_complete']) ) {
            $order[config('const.schedule_state.complete')] = $request['order_complete'];
        } else {
            $order[config('const.schedule_state.complete')] = '';
        }

        if ( !is_null($request['order_cancel']) ) {
            $order[config('const.schedule_state.cancel_senpai')] = $request['order_cancel'];
        } else {
            $order[config('const.schedule_state.cancel_senpai')] = '';
        }

        // search by term
        $start_date[config('const.schedule_state.complete')] = NULL;
        $end_date[config('const.schedule_state.complete')] = NULL;

        if ( !is_null($request['from_complete']) ) {
            $start_date[config('const.schedule_state.complete')] = $request['from_complete'];
        }

        if ( !is_null($request['to_complete']) ) {
            $end_date[config('const.schedule_state.complete')] = $request['to_complete'];
        }

        $start_date[config('const.schedule_state.cancel_senpai')] = NULL;
        $end_date[config('const.schedule_state.cancel_senpai')] = NULL;

        if ( !is_null($request['from_cancel']) ) {
            $start_date[config('const.schedule_state.cancel_senpai')] = $request['from_cancel'];
        }

        if ( !is_null($request['to_cancel']) ) {
            $end_date[config('const.schedule_state.cancel_senpai')] = $request['to_cancel'];
        }

        $kouhai_id = Auth::user()->id;

        // get lists
        $schedule_lists[config('const.schedule_state.reserve')] = LessonService::getScheduleListByKouhaiId(
            $kouhai_id,
            config('const.schedule_state.reserve'),
            $keyword[config('const.schedule_state.reserve')],
            $order[config('const.schedule_state.reserve')]
        )->paginate($this->schedules_per_page, ['*'], 'reserve');
        $counts[config('const.schedule_state.reserve')] = $schedule_lists[config('const.schedule_state.reserve')]->total();

        $schedule_lists[config('const.schedule_state.complete')] = LessonService::getScheduleListByKouhaiId(
            $kouhai_id,
            config('const.schedule_state.complete'),
            $keyword[config('const.schedule_state.complete')],
            $order[config('const.schedule_state.complete')],
            $start_date[config('const.schedule_state.complete')],
            $end_date[config('const.schedule_state.complete')]
        )->paginate($this->schedules_per_page, ['*'], 'complete');
        $counts[config('const.schedule_state.complete')] = $schedule_lists[config('const.schedule_state.complete')]->total();

        $schedule_lists[config('const.schedule_state.cancel_senpai')] = LessonService::getScheduleListByKouhaiId(
            $kouhai_id,
            config('const.schedule_state.cancel_senpai'),
            $keyword[config('const.schedule_state.cancel_senpai')],
            $order[config('const.schedule_state.cancel_senpai')],
            $start_date[config('const.schedule_state.cancel_senpai')],
            $end_date[config('const.schedule_state.cancel_senpai')]
        )->paginate($this->schedules_per_page, ['*'], 'cancel');
        $counts[config('const.schedule_state.cancel_senpai')] = $schedule_lists[config('const.schedule_state.cancel_senpai')]->total();

        return view('user.myaccount.student_lesson_history', [
            'schedule_lists' => $schedule_lists,
            'counts' => $counts,
            'state' => $state,
            'keyword' => $keyword,
            'order' => $order,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'title' => 'レッスン履歴', 'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function studentLessonRequest(Request $request, $schedule_id)
    {
        $schedule_info = LessonService::getScheduleInfoById($schedule_id);
        if ( is_null($schedule_info) ) {
            return redirect()->route('user.myaccount.student_lesson_history');
        }

        // user validation
        $kouhai_id = Auth::user()->id;
        if ( $schedule_info['lesson_request']['lr_user_id'] != $kouhai_id ) {
            return redirect()->route('user.myaccount.student_lesson_history');
        }

        $state = $schedule_info['lrs_state'];

        // coupon
        $schedule_info['coupon'] = CouponService::getCouponValue($schedule_info['lrs_id']);

        $title = '';
        $route = '';
        if ( $state == config('const.schedule_state.reserve') ) {
            $title = 'リクエスト中のレッスン';
            $route = 'user.myaccount.student_lesson_detail_doing';
        } elseif ( $state == config('const.schedule_state.complete') ) {
            $title = '完了したレッスン';
            $route = 'user.myaccount.student_lesson_detail_finish';
        } elseif ( $state >= config('const.schedule_state.cancel_senpai') ) {
            $title = 'キャンセル';
            $route = 'user.myaccount.student_lesson_detail_cancel';
        } else {
            return redirect()->route('user.myaccount.student_lesson_history');
        }

        return view($route, [
            'schedule_info' => $schedule_info,
            'title' => $title,
            'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function changeRequest_1(Request $request, $schedule_id)
    {
        $schedule_info = LessonService::getScheduleInfoById($schedule_id);
        if ( is_null($schedule_info) ) {
            return redirect()->route('user.myaccount.student_lesson_history');
        }

        // user validation
        $kouhai_id = Auth::user()->id;
        if ( $schedule_info['lesson_request']['lr_user_id'] != $kouhai_id ) {
            return redirect()->route('user.myaccount.student_lesson_history');
        }

        $lesson_id = $schedule_info['lesson_request']['lesson']['lesson_id'];

        $data['schedule_count'] = LessonService::getSchedulesCntByLessonId($lesson_id);
        $data['evaluation_count'] = EvalutionService::getLessonEvalutionCount($lesson_id, EvalutionService::KOUHAIS_EVAL);
        $data['evaluation'] = EvalutionService::getLessonEvalutionPercentByType($lesson_id, EvalutionService::KOUHAIS_EVAL);
        $data['senpai'] = $schedule_info['lesson_request']['lesson']['senpai'];
        $data['lesson'] = LessonService::getLessonInfo($lesson_id); // todo
        $data['dates'] = LessonService::getLessonSchedules($lesson_id);

        $week_start = date('Y-m-d', strtotime('-1 weeks  Sunday'));
        $end_week_day = Carbon::parse($week_start)->addWeeks(8);
        return view('user.myaccount.changerequest_1', [
            'schedule_id' => $schedule_info['lrs_id'],
            'data' => $data,
            'end_week_day' => $end_week_day,
            'title' => 'リクエスト内容の変更', 'page_id' => 'home', 'page_id_02' => '']);
    }

    public function changerequest_2(Request $request, $schedule_id)
    {
        $schedule_info = LessonService::getScheduleInfoById($schedule_id);
        if ( is_null($schedule_info) ) {
            return redirect()->route('user.myaccount.student_lesson_history');
        }

        // user validation
        $kouhai_id = Auth::user()->id;
        if ( $schedule_info['lesson_request']['lr_user_id'] != $kouhai_id ) {
            return redirect()->route('user.myaccount.student_lesson_history');
        }

        $min_hours = $schedule_info['lesson_request']['lesson']['lesson_min_hours'];

        // coupon
        $schedule_info['coupon'] = CouponService::getCouponValue($schedule_info['lrs_id']);

        return view('user.myaccount.changerequest_2', [
            'schedule_info' => $schedule_info,
            'min_hours' => $min_hours,
            'title' => 'リクエスト内容の変更', 'page_id' => 'home', 'page_id_02' => '']);
    }

    public function updateRequest(Request $request) {
        $params = $request->all();

        $schedule_info = LessonService::getScheduleInfoById($params['schedule_id']);
        if ( is_null($schedule_info) ) {
            return redirect()->route('user.myaccount.changerequest_2')->with('schedule_id', $params['schedule_id']);
        }

        // user validation
        $user_id = Auth::user()->id;
        if ( $schedule_info['lesson_request']['lr_user_id'] != $user_id ) {
            return redirect()->route('user.myaccount.student_lesson_history');
        }

        // cancel old schedule and set state into modified
        $res = LessonService::modifySchedule($params['schedule_id']);
        if ( !$res ) {
            return redirect()->route('user.myaccount.changerequest_2')->with('schedule_id', $params['schedule_id']);
        }

        $request_params = $schedule_info['lesson_request'];
        $request_params['lr_man_num'] = $params['lr_man_num'];
        $request_params['lr_woman_num'] = $params['lr_woman_num'];
        if ( $params['meeting_pos'] == config('const.change_position_type.lesson_position') ) {
            $request_params['lr_area_id'] = $request_params['lesson']['lesson_area_ids'];
            $request_params['lr_address'] = $request_params['lesson']['lesson_map_address'];
            $request_params['lr_address_detail'] = $request_params['lesson']['lesson_pos_detail'];
        } elseif ( $params['meeting_pos'] == config('const.change_position_type.set_position') ) {
            $request_params['lr_area_id'] = $params['area_id'];
            $request_params['lr_address'] = $params['address'];
            $request_params['lr_address_detail'] = $params['pos_detail'];
        }

        $request_params['lr_state'] = config('const.req_state.request');
        $request_params['lr_request_date'] = date('Y-m-d H:i:s');
        $request_params['lr_type'] = config('const.request_type.reserve');
        $request_params['lr_user_id'] = $user_id;
        $request_params['lr_lesson_id'] = $request_params['lesson']['lesson_id'];

        $res = LessonRequest::create($request_params->toArray());

        if ( !$res ) {
            return redirect()->route('user.myaccount.changerequest_2')->with('schedule_id', $params['schedule_id']);
        }

        if ( $params['no_change_schedule'] == 1 ) {
            $schedule_params['date'] = $schedule_info['lrs_date'];
            $schedule_params['start_time'] = $schedule_info['lrs_start_time'];
            $schedule_params['end_time'] = $schedule_info['lrs_end_time'];
        } else {
            $schedule_params = CommonService::getScheduleForDB($params['sel_schedule_html']);
        }

        $schedule_params['lr_id'] = $res['lr_id'];
        $schedule_params['30min_fees'] = $schedule_info['lesson_request']['lesson']['lesson_30min_fees'] * ($schedule_info['lesson_request']['lr_man_num'] + $schedule_info['lesson_request']['lr_woman_num']);
        $schedule_params['traffic_fee'] = $params['traffic_fee'];

        if ( $params['no_confirm'] == config('const.no_confirm.old_request') ) {
            $schedule_params['no_confirm'] = config('const.no_confirm.old_request');
            $schedule_params['old_schedule'] = $schedule_info['lrs_id'];
        } else {
            $schedule_params['no_confirm'] = config('const.no_confirm.cancel_request');
        }

        $schedule_params['lrs_kouhai_id'] = $user_id;
        $schedule_params['lrs_senpai_id'] = $res->lesson->senpai->id;

        $res = LessonService::doCreateRequestSchedule($schedule_params);
        if ( !$res ) {
            return redirect()->route('user.myaccount.changerequest_2')->with('schedule_id', $params['schedule_id']);
        }

        // send talkroom messages
        $senpai_id = $schedule_info['lesson_request']['lesson']['lesson_senpai_id'];
        $ls_title = $schedule_info['lesson_request']['lesson']['lesson_title'];
        $old_schedule = CommonService::getMDAndWeek($schedule_info['lrs_date']).
                        CommonService::getStartAndEndTime($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time']);
        $new_schedule = CommonService::getMDAndWeek($schedule_params['date']).
                        CommonService::getStartAndEndTime($schedule_params['start_time'], $schedule_params['end_time']);
        $old_schedule_id = $schedule_info['lrs_id'];
        $new_schedule_id = $res['lrs_id'];
        $req_id = $schedule_params['lr_id'];
        TalkroomService::saveSenpaiRequestChangeResponse($senpai_id, $user_id, $ls_title, $old_schedule, $new_schedule, $old_schedule_id, $new_schedule_id);
        TalkroomService::saveKouhaiRquestChangeSchedule($user_id, $senpai_id, $ls_title, $old_schedule, $new_schedule, $req_id);

        // send todo_list message
        MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $senpai_id, "request_change");

        return redirect()->route('user.myaccount.changerequest_3', [
            'schedule_id' => $res['lrs_id'],
            'old_schedule_id' => $schedule_info['lrs_id']
            ]);
    }

    public function changerequest_3(Request $request, $schedule_id, $old_schedule_id)
    {
        $new_schedule_info = LessonService::getScheduleInfoById($schedule_id);
        $new_schedule_info['coupon'] = CouponService::getCouponValue($new_schedule_info['lrs_id']);
        $old_schedule_info = LessonService::getScheduleInfoById($old_schedule_id);

        return view('user.myaccount.changerequest_3', [
            'new_schedule_info' => $new_schedule_info,
            'old_schedule_info' => $old_schedule_info,
            'title' => '', 'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function cancelStdntLsn(Request $request, $schedule_id)
    {
        $schedule_info = LessonService::getScheduleInfoById($schedule_id);
        if ( is_null($schedule_info) ) {
            return redirect()->route('user.myaccount.student_lesson_history');
        }

        $cancel_reason_types = CommonService:: getCancelReasonTypes();
        return view('user.myaccount.cancel_student_lesson', [
            'schedule_info' => $schedule_info,
            'cancel_reason_types' => $cancel_reason_types,
            'title' => 'レッスンのキャンセル', 'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function cancelStdntLsnSuccess(CancelScheduleRequest $request)
    {
        $schedule_info = LessonService::getScheduleInfoById($request['schedule_id']);
        if ( is_null($schedule_info) ) {
            return redirect()->route('user.myaccount.student_lesson_history');
        }

        // user validation
        $kouhai_id = Auth::user()->id;
        if ( $schedule_info['lesson_request']['lr_user_id'] != $kouhai_id ) {
            return redirect()->route('user.myaccount.student_lesson_history');
        }

        $res = LessonService::cancelSchedule($request['schedule_id'], $request['commitment'], config('const.user_type.cancel_kouhai'), $request['other_reason'], $request['cancel_fee']);
        if ( !$res ) {
            return redirect(route('user.myaccount.cancel_student_lesson', ['schedule_id' => $request['schedule_id']]));
        }

        // send talkroom message
        $senpai_id = $schedule_info['lesson_request']['lesson']['lesson_senpai_id'];
        // get cancel reasons
        $reasons = array();
        $obj_reasons = CancelReasonType::whereIn('crt_id', $request['commitment'])->get();
        foreach ($obj_reasons as $obj_reason) {
            if ( $obj_reason['crt_id'] == config('const.senpai_cancel_other_reason_id') ||
                $obj_reason['crt_id'] == config('const.kouhai_cancel_other_reason_id') ) {
                $reasons[] = $request['other_reason'];
                continue;
            }
            $reasons[] = $obj_reason['crt_content'];
        }

        $ls_title = $schedule_info['lesson_request']['lesson']['lesson_title'];
        $start_date = CommonService::getYMD($schedule_info['lrs_date']) . ' ' .
            date('H:i') . '~';
        $ls_img = CommonService::unserializeData($schedule_info['lesson_request']['lesson']['lesson_image'])[0];
        $cancel_amount = $request['cancel_fee'];

        TalkroomService::saveKouhaiRequestCancel($kouhai_id, $senpai_id, $reasons, $cancel_amount, $ls_title, $start_date, $ls_img);
        TalkroomService::saveSenpaiRequestKouhaiCancel($senpai_id, $kouhai_id, $reasons, $cancel_amount, $ls_title, $start_date, $ls_img );

        // send todo_list message
        MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $senpai_id, "request_cancel");

        return view('user.myaccount.cancel_student_lesson_1', [
            'schedule_info' => $schedule_info,
            'title' => '', 'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function setAccount(Request $request)
    {
        return view('user.myaccount.set_account', ['title' => 'アカウント設定', 'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function showChangeEmailForm(Request $request)
    {
        $user_info = Auth::user();
        return view('user.myaccount.set_mail_address', [
            'user_info' => $user_info,
            'title' => 'メールアドレス', 'page_id' => '', 'page_id_02' => '']);
    }

    public function updateEmailAddress(Request $request) {
        $update_params = $request->only(['email', 'password']);

        $error_messages = array();
        $attributes = array();
        $attributes['email'] = "メールアドレス";
        $attributes['password'] = "パスワード";

        $hashed_password = Auth::user()->password;

        $validator = validator::make($update_params, [
            'email' => 'required|email|string|unique:users,email',
            'password' => 'required|min:7|password_check:'.$hashed_password,
        ], $error_messages, $attributes);

        if ( $validator->fails() ) {
            $errors = $validator->errors();
            return response()->json(["result_code" => "failed", "res" => $errors], 200);
        }

        $update_param['email'] = $update_params['email'];
        $res = Auth::user()->update($update_param);
        if ( !$res ) {
            // failed
            return response()->json(["result_code" => "failed", "redirect_url" => route('user.myaccount.show_email_form')], 200);
        }

        return response()->json(["result_code" => "success"], 200);
    }

    public function showSetPwdForm(Request $request) {
        return view('user.myaccount.set_password', ['title' => 'パスワード', 'page_id' => '', 'page_id_02' => '']);
    }

    public function setPassword(Request $request)
    {
        $validate_params = $request->only(['old_password', 'password', 'password_confirmation']);

        $validator = $this->validatePassword($validate_params);

        if ( $validator->fails() ) {
            $errors = $validator->errors();
            return response()->json(["result_code" => "failed", "res" => $errors]);
        }

        $res = Auth::user()->update(['password' => Hash::make($validate_params['password'])]);
        if ( !$res ) {
            //failed
            return response()->json(["result_code" => "failed", "redirect_url" => route('user.myaccount.show_password_form')], 200);
        }

        return response()->json(["result_code" => "success"], 200);
    }

    public function validatePassword($data) {

        $error_messages = array();
        $attributes = array(
            'old_password' => 'パスワード',
            'password' => '新しいパスワード'
        );

        $hashed_password = Auth::user()->password;

        $validator = validator::make($data, [
            'old_password' => 'required|password_check:'.$hashed_password,
            'password' => 'required|confirmed|min:7'
        ], $error_messages, $attributes);

        return $validator;
    }

    public function showPhoneForm(Request $request)
    {
        $phone = Auth::user()->user_phone;
        return view('user.myaccount.set_phone', [
            'phone' => $phone,
            'title' => '電話番号', 'page_id' => 'mypage', 'page_id_02' => '']);
    }

    public function setPhone(PhoneRequest $request) {
        $request->getSession()->put('user_phone', $request['phone']);

        return redirect(route('user.register.verify_phone.form', ['from' => 1]));
    }

    public function verifyPhone(Request $request) {
        // todo sending SMS //
        /////////////////////

        $verify_result = 1;
        if ( !$verify_result ) {
            return view('user.myaccount.reg_verify_phone', ['page_id' => '', 'page_id_02' => '']);
        }

        // verified
        $phone_data['user_phone'] = $request->session()->get('user_phone');
        $res = Auth::user()->update($phone_data);
        if ( $res ) {
            return redirect()->route('user.myaccount.set_account');
        }

        //failed
    }

    public function showSetUserInfo(Request $request)
    {
        $user_info = Auth::user();
        return view('user.myaccount.reg_user', [
            'user_info' => $user_info,
            'title' => 'ユーザー登録', 'page_id' => '', 'page_id_02' => '']);
    }

    public function setUserInfo(Request $request) {
        $update_params = $request->only([
            'user_firstname',
            'user_lastname',
            'user_sei',
            'user_mei',
            'user_birthday',
            'user_sex',
            'user_mail',
            'user_area_id',
            'user_county',
            'user_village',
            'user_mansyonn'
        ]);

        $obj_user = Auth::user();
        $update_params['user_is_senpai'] = $obj_user->user_is_senpai;

        $validator = $this->validateUserInfo($update_params);
        if ( $validator->fails() ) {
            $errors = $validator->errors();
            return response()->json([
                'result_code' => 'failed',
                'errors' => $errors,
            ]);
        }

        $res = $obj_user->update($update_params);
        if ( $res ) {
            return response()->json([
                'result_code' => 'success',
            ]);
        }

        return redirect()->route('user.myaccount.set_user.form');

    }

    public function validateUserInfo($data) {
        $error_message = array(
            'user_sex.required' => '性別を選択してください。',
            'user_sex.min' => '性別を選択してください。'
        );

        $attributes = array(
            'user_firstname' => '姓',
            'user_lastname' => "名",
            'user_sei' => "姓",
            'user_mei' => "名",
            'user_birthday' => "生年月日",
            'user_sex' => "性別",
            'user_mail' => "郵便番号",
            'user_area_id' => "都道府県",
            'user_county' => "市区町村",
            'user_village' => "町番地",
            'user_mansyonn' => "マンション名・部屋番号"
        );

        // 後輩
        $validator = validator::make($data, [
            'user_firstname' => "required|string|max:50",
            'user_lastname' => "required|string|max:50",
            'user_sei' => "required|string|max:50",
            'user_mei' => "required|string|max:50",
            'user_birthday' => "required|date",
            'user_sex' => "required|numeric|min:1",
            'user_area_id' => "required|string"
        ], $error_message, $attributes);

        if (isset($data['user_is_senpai']) && $data['user_is_senpai'] == config('const.user_type.senpai')) {
            // 先輩
            $validator = validator::make($data, [
                'user_firstname' => "required|string|max:50",
                'user_lastname' => "required|string|max:50",
                'user_sei' => "required|string|max:50",
                'user_mei' => "required|string|max:50",
                'user_birthday' => "required|date",
                'user_sex' => "required|numeric|min:1",
                'user_mail' => "required|string",
                'user_area_id' => "required|string",
                'user_county' => "required|string",
                'user_village' => "required|string",
                'user_mansyonn' => "required|string"
            ], $error_message, $attributes);
        }

        return $validator;
    }


    public function account(Request $request, $prev_url_id)
    {
        $params = $request->all();
        return view('user.myaccount.account_bank',
            [   'title' => '売上振込先の口座情報',
                'page_id' => 'mypage',
                'page_id_02' => 'bank',
                'prev_url_id' => $prev_url_id,
                'obj_user' => Auth::user(),
                'condition' => $params
            ]);
    }

    public function validateAccount($data) {

        $error_messages = array();
        $attributes = array(
            'bank' => '金融機関名',
            'bank_account_type' => '口座種別',
            'bank_branch' => '支店コード',
            'bank_account_no' => '口座番号',
            'bank_account_name' => '口座名義'
        );

        $validator = validator::make($data, [
            'bank' => 'required',
            'bank_account_type' => 'required',
            'bank_branch' => 'required|string|max:50',
            'bank_account_no' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:50',
        ], $error_messages, $attributes);

        return $validator;
    }

    public function ajaxAddAccount(Request $request)
    {
        // $params = $request->only('act_id', 'act_bank_id', 'bnk_name', 'act_type', 'act_type_name', 'act_suboffice_code', 'act_number', 'act_name');
        $params = $request->all();
        $validator = $this->validateAccount($params);
        if ( $validator->fails() ) {
            $errors = $validator->errors();
            return response()->json(
                [
                    "result_code" => "failed",
                    "res" => $errors
                ]);
        }

        /*$account = array();
        $account['act_user_id'] = Auth::user()->id;
        $account['act_bank_id'] = $params['act_bank_id'];
        $account['act_type'] = $params['act_type'];
        $account['act_suboffice_code'] = $params['act_suboffice_code'];
        $account['act_number'] = $params['act_number'];
        $account['act_name'] = $params['act_name'];
        if($params['act_id'] > 0){
            $add_result = BankService::doUpdateAccount($account, $params['act_id']);
        }else{
            $add_result = BankService::doCreateAccount($account);
        }
        if(!is_null($add_result)){
            $result = true;
        }else{
            $result = false;
        }*/

        if (Auth::user() && UserService::updateUserInfo(Auth::user()->id, $params)) {
            $result = true;
        } else {
            $result = false;
        }

        return response()->json(
            [
                "result_code" => "success",
                "result" => $result
            ]);
    }

    public function selBankNew(Request $request)
    {
        $params = $request->all();
        $alphabet_arr = config('const.alphabet');
        $alphaBankList = [];
        foreach ($alphabet_arr as $key => $value){
            $alphaBankList[$key] = [];
        }

        $request->session()->put('alpha_bank_list', $alphaBankList);
        return view('user.myaccount.sel_bank_new',
            [   'title' => '銀行の選択',
                'page_id' => 'mypage',
                'page_id_02' => 'bank',
                'condition' => $params,
                'alpha_bank_list' => $alphaBankList,
                'alphabet_arr' => $alphabet_arr,
            ]);
    }

    public function selBankAlphabet(Request $request)
    {
        $params = $request->all();

        $alphaBankList = $request->getSession()->get('alpha_bank_list');
        $bank_master = BankService::getBankByKeyword(config('const.alphabet_code.' . $params['alpha']));
        return view('user.myaccount.sel_bank_alphabet',
            [   'title' => '銀行の選択',
                'page_id' => 'mypage',
                'page_id_02' => 'bank',
                'condition' => $params['condition'],
                'bank_list' => $bank_master,
                'alpha' => $params['alpha'],
            ]);
    }

    public function selAccountType(Request $request)
    {
        $params = $request->all();
        return view('user.myaccount.sel_account_type',
            [   'title' => '口座種別の選別',
                'page_id' => 'mypage',
                'page_id_02' => 'bank',
                'condition' => $params
            ]);
    }

    //njh
    public function blockOutline(Request $request)
    {
        $user_id = Auth::user()->id;
        $blocks = BlockService::getBlockList($user_id);
        if(count($blocks)){
            return view('user.myaccount.block_outline', ['page_id' => 'block_outline_1', 'page_id_02' => '', 'data' => $blocks]);
        }else{
            return view('user.myaccount.empty_block_outline', ['page_id' => 'block_outline_1', 'page_id_02' => '']);
        }

    }

    public function ajaxDelBlock(Request $request)
    {
        $del_bl_id = $request->get('bl_id');
        $block = BlockService::getBlock($del_bl_id);
        $result = BlockService::deleteBlock($del_bl_id);
        return response()->json(
            [
                "result_code" => "success",
                'block_name' => $block['user']['name'],
                'result' => $result
            ]);

    }

    public function pushAndMail(Request $request)
    {
        $user_id = Auth::user()->id;
        $msg_class = MessageService::getMsgClassAndSettingList($user_id);
        return view('user.myaccount.push_and_mail',
            [
                'page_id' => 'push_and_mail',
                'page_id_02' => '',
                'msg_class' => $msg_class
            ]);
    }

    public function ajaxPushAndMail(Request $request)
    {
        $params = $request->only('ms_mc_id', 'ms_push', 'ms_email');
        $params['ms_user_id'] = Auth::user()->id;
        MessageService::insertMsgSetting($params);
        return response()->json(
            [
                "result_code" => "success",
            ]);
    }

    public function others(Request $request)
    {
        return view('user.myaccount.others', ['page_id' => 'others', 'page_id_02' => '']);
    }
    public function companyAbstract(Request $request)
    {
        return view('user.myaccount.company_abstract', ['page_id' => 'others', 'page_id_02' => '']);
    }
    public function useRule(Request $request)
    {
        return view('user.myaccount.use_rule', ['page_id' => 'others', 'page_id_02' => '']);
    }
    public function personalInfo(Request $request)
    {
        return view('user.myaccount.personal_info', ['page_id' => 'others', 'page_id_02' => '']);
    }
    public function saleMethod(Request $request)
    {
        return view('user.myaccount.sale_method', ['page_id' => 'others', 'page_id_02' => '']);
    }
    public function payMethod(Request $request)
    {
        return view('user.myaccount.pay_method', ['page_id' => 'others', 'page_id_02' => '']);
    }


    public function saleDetailList(Request $request)
    {
        // 売上明細書 => 売上期間
        $years_list = LessonService::getRequestScheduleYear();
        $current_year = Carbon::now()->format('Y');
        return view('user.myaccount.sale_detail_list', [
            'page_id' => 'sale_detail_list',
            'years_list' => $years_list,
            'current_year' => $current_year,
            'page_id_02' => ''
        ]);
    }

    public function saleDetailNote(Request $request)
    {
        $params = $request->all();
        $obj_user = Auth::user();
        $title = Carbon::parse($params['date'])->format('Y年n月　売上明細書');
        return view('user.myaccount.sale_detail_note', [
            'title' => $title,
            'page_id' => 'sale_detail_note',
            'date' => $params['date'],
            'price' => $params['price'],
            'obj_user' => $obj_user,
            'page_id_02' => ''
        ]);
    }

    public function asking(Request $request)
    {
        // $user_type = QuestionService::getQuesClassFromParent(config('const.ques_type.to_manager'));
        return view('user.myaccount.asking',
            [
                'page_id' => 'asking',
                'page_id_02' => ''
            ]);
    }

    public function validateAsking($data) {

        $error_messages = array();
        $attributes = array(
            'ask' => 'お問い合わ'
        );

        $validator = validator::make($data, [
            'ask' => 'required|string|max:1000'
        ], $error_messages, $attributes);

        return $validator;
    }

    public function ajaxAddAsking(Request $request)
    {
        $params = $request->only(['qc_id', 'user_type', 'ask']);
        $validator = $this->validateAsking($params);
        if ( $validator->fails() ) {
            $errors = $validator->errors();
            return response()->json(
                [
                    "result_code" => "failed",
                    "res" => $errors
                ]);
        }

        $contact = array();
        $contact['user_id'] = Auth::user()->id;
        $contact['user_type'] = $params['user_type'];
        $contact['contact_type'] = $params['qc_id'];
        $contact['content'] = $params['ask'];
        $obj_contact = ContactService::doCreateContact($contact);
        if(is_object($obj_contact)){
            $result = true;
        }else{
            $result = false;
        }
        return response()->json(
            [
                "result_code" => "success",
                "result" => $result
            ]);
    }

    public function ajaxQuesClass(Request $request)
    {
        $params = $request->only(['qc_id']);
        // old code from table
        /*$result = QuestionService::getQuesClassFromParent($params['qc_id']);*/

        // new code from const
        $result = config('const.ask_type');
        return response()->json(
            [
                "result_code" => "success",
                "ques_class" => $result
            ]);
    }

    public function ajaxQuestion(Request $request)
    {
        $params = $request->only(['qc_id']);
        $result = QuestionService::getQuestionsWithAnswerFromQcId($params['qc_id']);
        return response()->json(
            [
                "result_code" => "success",
                "question" => $result
            ]);
    }


    public function quit(Request $request)
    {
        return view('user.myaccount.quit', ['page_id' => 'quit', 'page_id_02' => '']);
    }

    public function ajaxSetQuit(Request $request)
    {
        $user_id = Auth::user()->id;
        //kouhai
        $kouhai_shchedule_list = LessonService::getScheduleListByKouhaiId($user_id, config('const.quit_state'));
        foreach ($kouhai_shchedule_list->get() as $k => $v){
             if(!LessonService::updateScheduleState($v['lrs_id'], config('const.schedule_state.cancel_kouhai'))){
                 return response()->json(
                     [
                         "result" => false
                     ]);
             }
        }
        $kouhai_request_list = LessonService::getRequestLstByKouhaiId($user_id, config('const.quit_state'));
        foreach ($kouhai_request_list as $k => $v){
            if(!LessonService::updateLessonReqState($v['lr_id'], config('const.req_state.cancel'))){
                return response()->json(
                    [
                        "result" => false
                    ]);
            }
        }

        //senpai
        $user = UserService::getUserByID($user_id);
        if(empty($user)){
            return response()->json(
                [
                    "result" => false
                ]);
        }
        if($user['user_is_senpai']){
            $lesson_list = LessonService::getLessonListBySenpai($user_id);
            foreach ($lesson_list as $k => $v){
                if(!LessonService::updateLessonState($v['lesson_id'], config('const.lesson_state.delete'))){
                    return response()->json(
                        [
                            "result" => false
                        ]);
                }
            }

            $senpai_request_list = LessonService::getRequestLstBySenpaiId($user_id, config('const.quit_state'));
            foreach ($senpai_request_list as $k => $v){
                if(!LessonService::updateLessonReqState($v['lr_id'], config('const.req_state.cancel'))){
                    return response()->json(
                        [
                            "result" => false
                        ]);
                }
            }

            $senpai_shchedule_list = LessonService::getScheduleListBySenpaiId($user_id, config('const.quit_state'));
            foreach ($senpai_shchedule_list->get() as $k => $v){
                if(!LessonService::updateScheduleState($v['lrs_id'], config('const.schedule_state.cancel_senpai'))){
                    return response()->json(
                        [
                            "result" => false
                        ]);
                }
            }
        }
        if(!UserService::deleteUser($user_id)){
            return response()->json(
                [
                    "result" => false
                ]);
        }
        return response()->json(
            [
                "result" => true
            ]);
    }
    //kh
    public function friendInvite(Request $request)
    {
        $result_code = CommonService::createInviteCode();

        return view('user.myaccount.friend_invite', ['page_id' => 'talkroom',
            'page_id_02' => '',
            'title'=>'お友達を招待する',
            'sub_title'=>'',
            'invite_code' => $result_code]);
    }

    public function couponIntro(Request $request, $mode=1)
    {
        $user_id = Auth::user()->id;

        $coupons = CouponService::getCouponlist($user_id)->paginate($this->per_page);
        $pages = $coupons->lastPage();

        if($mode == 2){
            return response()->json(
                [
                    "result_code" => "success",
                    'coupons' => $coupons
                ]);
        }
        return view('user.myaccount.coupon_intro',
            ['page_id' => 'talkroom',
                'page_id_02' => '',
                'title'=>'クーポン管理',
                'sub_title'=>'',
                'coupons' => $coupons,
                'pages' => $pages
                ]);
    }

    public function couponPublish(Request $request)
    {
        return view('user.myaccount.coupon_publish', ['page_id' => 'talkroom', 'page_id_02' => '', 'title'=>'クーポン発行', 'sub_title'=>'']);
    }

    public function postCouponPublish(Request $request)
    {
        $params = $request->only(['coupon_name',
            'coupon_condition',
            'coupon_number',
            'coupon_period',
            'coupon_discount',
            'coupon_buy',
        ]);
        $result = array();
        $result_code = 'success';
        if(!(isset($params['coupon_name']) && strlen($params['coupon_name']) < 31 ))
        {
            $result_code = "error";
            $result['error']['coupon_name'] = "クーポン名を正確に入力してください。";
        }

        if(!(isset($params['coupon_condition']) && is_numeric($params['coupon_condition']) && ($params['coupon_condition'] >= 1000) && ($params['coupon_condition'] <= 100000)  ))
        {
            $result_code = "error";
            $result['error']['coupon_condition'] = "適用条件を正確に入力してください。";
        }

        $nStart = 0;
        $nEnd = 0;
        if(!(isset($params['coupon_number']) && is_numeric($params['coupon_number']) && round($params['coupon_number']) > 1 && round($params['coupon_number']) < 6 ))
        {
            $result_code = "error";
            $result['error']['coupon_number'] = "クーポンのセット枚数を正確に入力してください。";
        } else
        {
            $nStart = ($params['coupon_number'] - 1) * 10;
            $nEnd = (2 * $params['coupon_number'] - 1) * 10;
        }

        if(!(isset($params['coupon_period']) && is_numeric($params['coupon_period']) && round($params['coupon_period']) >= $nStart && round($params['coupon_period']) <= $nEnd))
        {
            $result_code = "error";
            $result['error']['coupon_period'] = "有効期限を正確に入力してください。";
        }


        if(!(isset($params['coupon_discount']) && is_numeric($params['coupon_discount']) && ($params['coupon_discount'] >= 1000) && ($params['coupon_discount'] <= 100000)  ))
        {
            $result_code = "error";
            $result['error']['coupon_discount'] = "割引金額を正確に入力してください。";
        }

        if(!(isset($params['coupon_buy']) && is_numeric($params['coupon_buy']) && ($params['coupon_buy'] >= 1000) && ($params['coupon_buy'] <= 100000)  ))
        {
            $result_code = "error";
            $result['error']['coupon_buy'] = "販売金額を正確に入力してください。";
        }

        if($result_code == 'success')
        {
            $params['user_id'] = Auth::user()->id;
            CouponService::doCreateCoupon($params);
        }

        return response()->json(
            [
                "result_code" => $result_code,
                'message' => $result
            ]);
    }

    public function couponBox(Request $request, $mode)
    {
        $order = config('const.coupon_sort.period_short');

        if(isset($request->only(['order'])['order']))
        {
            $order = $request->only(['order'])['order'];
        }
        $code = isset($request->only(['code'])['code']) ? $request->only(['code'])['code'] : "";
        $coupons = CouponService::getCouponBox(Auth::user()->id, config('const.coupon_state.valid'), 0, $code);

        return view('user.myaccount.coupon_box',
            ['page_id' => 'talkroom',
                'page_id_02' => '',
                'title'=>'クーポンBOX',
                'sub_title'=>'',
                'coupons' => $coupons,
                'order' => $order,
                'code' => $code
                ]);
    }

    public function confirm(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            return redirect('/login');
        }
        $user_id = Auth::user()->id;
        $user_info = UserService::getUserByID($user_id);

        $year_100 = date("Y") - 100;
        $year_10 = $year_100 + 90;

        $user_info['birth_year'] = date('Y', strtotime($user_info['user_birthday']));
        $user_info['birth_month'] = date('n', strtotime($user_info['user_birthday']));
        $user_info['birth_day'] = date('d', strtotime($user_info['user_birthday']));

        return view('user.myaccount.confirm', ['page_id' => 'talkroom',
            'page_id_02' => '',
            'title'=>'本人確認',
            'sub_title'=>'',
            'user_info' => $user_info,
            'year_100' => $year_100,
            'year_10'  => $year_10
            ]);
    }

    public function postUserInfo(Request $request)
    {
        $params = $request->only([
            'firstname',
            'lastname',
            'sei',
            'mei',
            'year',
            'month',
            'day',
            'sex',
        ]);

        $result = ['code'=>'success'];
        if(!(isset($params['firstname']) && strlen($params['firstname']) <= 50)){
            $result['code'] = 'error';
            $result['msg']['firstname'] = '氏名の姓を正確に入力してください。';
        }

        if(!(isset($params['lastname']) && strlen($params['lastname']) <= 50)){
            $result['code'] = 'error';
            $result['msg']['lastname'] = '氏名の名を正確に入力してください。';
        }

        if(!(isset($params['sei']) && strlen($params['sei']) <= 50)){
            $result['code'] = 'error';
            $result['msg']['sei'] = 'フリガナの姓を正確に入力してください。';
        }

        if(!(isset($params['mei']) && strlen($params['mei']) <= 50)){
            $result['code'] = 'error';
            $result['msg']['mei'] = 'フリガナの名を正確に入力してください。';
        }

        if( !(isset($params['year']) && isset($params['month']) && isset($params['day']) && (strtotime($params['year'].'-'.$params['month'].'-'.$params['day']) !=false) ))
        {
            $result['code'] = 'error';
            $result['msg']['birthday'] = '生年月日を正確に選択してください。';
        }

        if(!(isset($params['sex']) && $params['sex'] > 0)){
            $result['code'] = 'error';
            $result['msg']['sex'] = '性別を正確に選択してください。';
        }

        if($result['code'] == 'success')
        {
            $data = array();
            $data['user_firstname'] = $params['firstname'];
            $data['user_lastname'] = $params['lastname'];
            $data['user_sei'] = $params['sei'];
            $data['user_mei'] = $params['mei'];
            $data['user_birthday'] = $params['year'] . '-' . $params['month'] . '-' . $params['day'];
            $data['user_sex'] = $params['sex'];

            UserService::updateUserInfo(Auth::user()->id, $data);
        }
        return response()->json($result);
    }

    public function confirmDrive(Request $request)
    {
        return view('user.myaccount.confirm_drive', ['page_id' => 'talkroom', 'page_id_02' => '', 'title'=>'運転免許証／履歴証明書の提出', 'sub_title'=>'']);
    }

    public function confirmHealth(Request $request)
    {
        return view('user.myaccount.confirm_health', ['page_id' => 'talkroom', 'page_id_02' => '', 'title'=>'健康保険証の提出', 'sub_title'=>'']);
    }

    public function confirmNumber(Request $request)
    {
        return view('user.myaccount.confirm_number', ['page_id' => 'talkroom', 'page_id_02' => '', 'title'=>'マイナンバーの提出', 'sub_title'=>'']);
    }

    public function confirmStudent(Request $request)
    {
        return view('user.myaccount.confirm_student', ['page_id' => 'talkroom', 'page_id_02' => '', 'title'=>'学生証の提出', 'sub_title'=>'']);
    }

    public function confirmPassport(Request $request)
    {
        return view('user.myaccount.confirm_passport', ['page_id' => 'talkroom', 'page_id_02' => '', 'title'=>'パスポートの提出', 'sub_title'=>'']);
    }

    public function confirmJyumin(Request $request)
    {
        return view('user.myaccount.confirm_jyumin', ['page_id' => 'talkroom', 'page_id_02' => '', 'title'=>'住民票の提出', 'sub_title'=>'']);
    }

    public function confirmResident(Request $request)
    {
        return view('user.myaccount.confirm_resident', ['page_id' => 'talkroom', 'page_id_02' => '', 'title'=>'在留カードの提出', 'sub_title'=>'']);
    }

    public function confirmPermanent(Request $request)
    {
        return view('user.myaccount.confirm_permanent', ['page_id' => 'talkroom', 'page_id_02' => '', 'title'=>'特別永住者証明書の提出', 'sub_title'=>'']);
    }

    public function confirmAgain(Request $request)
    {
        return view('user.myaccount.confirm_again', ['page_id' => 'talkroom', 'page_id_02' => '', 'title'=>'本人確認', 'sub_title'=>'']);
    }

    public function quesCate(Request $request)
    {
        $ques_top_class = QuestionService::getQuesClassFromParent(0);
        $ques_serve = new QuestionService();
        //$ques_freq = $ques_serve->getQuesFromParent(0, config('const.ques_freq.yes'))->limit($this->page_limit)->get()->toArray();
        $ques_freq = $ques_serve->getNormalQuestion(0)->get()->toArray();

        return view('user.myaccount.ques_cate',
            ['page_id' => 'talkroom',
                'page_id_02' => '',
                'title'=>'よくある質問',
                'sub_title'=>'',
                'classes' => $ques_top_class,
                'questions' => $ques_freq]);
    }

    public function quesList(Request $request, $id)
    {
        $classname = QuestionService::getQuesClassFromId($id)[0]['qc_name'];
        $ques_serve = new QuestionService();
        $questions = $ques_serve->getQuesFromParent($id, config('const.ques_freq.not'))->paginate($this->per_page);

        return view('user.myaccount.ques_list',
            ['page_id' => 'talkroom',
                'page_id_02' => '',
                'title'=>'センパイについて',
                'sub_title'=>'',
                'classname' => $classname,
                'questions' => $questions,
                'id' => $id
                ]);
    }

    public function quesCateSmall(Request $request, $id)
    {
        $ques_class = QuestionService::getQuesClassFromParent($id);
        $ques_serve = new QuestionService();
        // $ques_freq = $ques_serve->getQuesFromParent($id, config('const.ques_freq.yes'))->get()->toArray();
        $ques_freq = $ques_serve->getNormalQuestion($id)->get()->toArray();

        return view('user.myaccount.ques_cate_small',
            ['page_id' => 'talkroom',
                'page_id_02' => '',
                'title'=>'センパイについて',
                'sub_title'=>'',
                'classes' => $ques_class,
                'questions' => $ques_freq,
                'id' => $id
                ]);
    }

    public function quesSearch(Request $request)
    {
        $params = $request->only([
            'keyword',
            'id']);

        $questions = QuestionService::doSearchQuestion($params);
        $total_count = $questions->count();
        $questions = $questions->paginate($this->per_page);

        return view('user.myaccount.ques_search',
            ['page_id' => 'talkroom',
                'page_id_02' => '',
                'title'=>'検索結果',
                'sub_title'=>'',
                'questions' => $questions,
                'total_count' => $total_count,
                'keyword' => $params['keyword'],
                'id' => $params['id']
            ]);
    }

    public function quesDetail(Request $request, $id)
    {

        $question = QuestionService::getQuestionFromId($id);
        $page_from = $request->input('page_from', "");
        $keyword = $request->input('keyword', "");

        // よくある質問=>アクセス数
        $user = Auth::User();
        QuestionService::setAccessQuestion($user, $id);

        return view('user.myaccount.ques_detail',
            ['page_id' => 'talkroom',
                'page_id_02' => '',
                'title'=>'検索結果',
                'sub_title'=>'',
                'page_from' => $page_from,
                'keyword' => $keyword,
                'question' => $question
                ]);
    }

    public function putConfirmDrive(Request $request)
    {
        $uploaded_file = $request->file('image_file');
        if(!isset($uploaded_file))
        {
            return redirect()->route('user.myaccount.confirm');
        }
        $pc_confirm_type = $request->input('pc_confirm_type');

        $file_name = $request->only('file_name')['file_name'];
        $uploaded_file->storeAs('public/credit/', $file_name);

        $data['pc_user_id'] = Auth::user()->id;
        $data['pc_confirm_doc'] = $file_name;
        $data['pc_state'] = config('const.pers_conf.make');
        $data['pc_confirm_type'] = $pc_confirm_type;

        PersonConfirmService::createPersonConfirm($data);
        return redirect()->route('user.myaccount.index');
    }

    //kkj
    public static function regSenpai()
    {
        return view('user.myaccount.reg_senpai',
            [
                'page_id' => 'talkroom',
                'page_id_02' => '',
                'title'=>'センパイ登録',
                'sub_title'=>'',
                'user_info'=>Auth::user()
            ]);
    }

    public static function postRegSenpai(Request $request)
    {
        $params = $request->post();
        if (UserService::updateUserInfo(Auth::user()->id, $params)){
            return response()->json(["state" => "success"]);
        } else{
            return response()->json(["state" => "error"]);
        }
    }

    public static function uploadMapLocation(Request $request)
    {
        $params = $request->all();
        if (isset($params['user_id']) && $params['user_id']) {
            if(UserService::setMapLocation($params['user_id'], $params['location'])) {
                return response()->json([
                    "result" => "success"
                ]);
            }
        }
        return response()->json(["result" => "failed"]);
    }

    public static function couponDelete(Request $request)
    {
        $params = $request->all();
        $result_code = "";
        $msg = "";
        if (isset($params['cup_id']) && $params['cup_id']) {
            if(CouponService::doDeleteCoupon($params)) {
                $result_code = "success";
                $msg = 'クーポンを削除しました。';
            } else {
                $result_code = "failed";
                $msg = 'クーポン削除が失敗しました。';
            }
        }
        return response()->json(
        [
            "result_code" => $result_code,
            'message' => $msg
        ]);
    }

    public static function getPaymentWithCondition(Request $request)
    {
        $params = $request->all();
        $start_date = $params['month'];
        $end_date = Carbon::parse($start_date)->format('Y-m-t');
        $label = Carbon::parse($start_date)->format('Y/n/j').'~'.Carbon::parse($end_date)->format('n/j');
        $obj_user = Auth::user();
        $amount_history = LessonService::getPaymentHistoryByPeriod($obj_user->id, $start_date, $end_date);
        return response()->json([
            'result_code' => 'success',
            'payment_list' => view('user.myaccount.payment_mgr_history', ['label'=>$label, 'amount_history'=>$amount_history])->render(),
        ]);
    }

    public static function getPaymentByYear(Request $request)
    {
        $params = $request->all();
        $obj_user = Auth::user();

        $date_interval = \DateInterval::createFromDateString("1 month");
        $start_date = new \DateTime($params['year']."-01-01");
        $end_date = new \DateTime($params['year']."-12-31");
        $period = new \DatePeriod($start_date, $date_interval, $end_date);
        $period_application = [];
        foreach($period as $dt) {
            $date_key = Carbon::parse($dt)->format('Y/m/t');
            $period_application[$date_key] = LessonService::getPeriodApplication($obj_user->id, $date_key);
        }

        return response()->json([
            'result_code' => 'success',
            'payment_list' => view('user.myaccount.payment_price_history', ['amount_history'=>$period_application])->render(),
        ]);
    }
}

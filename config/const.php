<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */
    'google_api_key' => env('GOOGLE_MAP_KEY'),
    'noreply_email' => env('MAIL_FROM_ADDRESS', 'apply@senpai.jp'),
    'mail_subject_prefix'=>'【センパイ】',
    'cancel_kind' =>[
        'kouhai' => 0,
        'senpai' => 1,
        'senpai_cancel' => 2,
    ],

    // 予約時間
    'schedule_reserve_time_before_limit_min' => 5,

    // トークルームのキャンセル申請メッセージ表示の limit time(minutes)
    'lesson_cancel_alarm_time' => 15,

    'kouhai_cancel_other_reason_id' =>5,
    'senpai_cancel_reason_id' =>10,
    'senpai_cancel_other_reason_id' =>14,

    'menu_type' =>[
        'kouhai' => 0,
        'senpai' => 1
    ],

    'fav_type' => [
        'lesson' => 0,
        'user' => 1,
        'recruit' => 2,
    ],

    'lesson_type' =>[
      'consult' =>0,
      'online' => 1
    ],
    'lesson_state' => [
        'draft' => 0,
        'private' => 1,
        'check' => 2,
        'public' => 3,
        'reject' => 4,
        'delete' => 5
    ],

    'lesson_tab' => [
        'public' => 1,
        'checking' => 2,
        'draft' => 3
    ],

    'lesson_cond_cnt' => 12,

    'request_type'=>[
        'reserve'=>0,
        'attend'=>1
    ],

    'request_type_category'=>[
        0=>'予約',
        1=>'出動'
    ],

    'req_state'=>[
        'request'=>0,
        'response'=>1,
        'reserve'=>2,
        'complete'=>3,
        'cancel'=>4,
        'reject'=>5
    ],

    'schedule_state'=>[
        'request'=>0,
        'confirm'=>1,
        'reserve'=>2,
        'start'=>3,
        'complete'=>4,
        'cancel_senpai'=>5,
        'cancel_kouhai'=>6,
        'cancel_system'=>7,
        'modified'=>8,
        'reject_senpai'=>9,
    ],

    'quit_state' => 255,

    'fee_type'=>[
        'a'=>0,
        'b'=>1,
        'c'=>2
    ],

    'prop_state' => [
        'request' => 0,
        'proposing' => 1,
        'complete' => 2
    ],

    'recruit_state' => [
        'all' => -1,
        'draft' => 0,
        'request' => 1,
        'recruiting' => 2,
        'complete' => 3,
        'past' => 4,
        'cancel' => 5,
        'reserve_not_success' => 6,
    ],
    'recruit_order' => [
        'fav' => 0,
        'new' => 1,
        'old' => 2,
        'unit_price' => 3,
        'payment' => 4,
        'remain_time_large' => 5,
        'remain_time_small' => 6,
    ],
    'gender_type' => [
        0 => '指定なし',
        1 => '女性',
        2 => '男性',
    ],
    'sex' => [
        'uncertain' => 0,
        'woman' => 1,
        'man' => 2,
    ],
    'ques_freq' => [
        'not' => 0,
        'yes' => 1,
    ],
    'pers_conf' =>[
        'make' => 0,
        'review' => 1,
        'confirmed' => 2,
        'rejected' => 3,
    ],
    'ques_type' =>[
        'question' => 1,
        'to_manager' => 2,
        'menu' => 3,
    ],

    'question_category_public_status' =>[
        0 => '非公開',
        1 => '公開'
    ],

    'question_category_public' =>[
        'no_public' => 0,
        'public' => 1
    ],

    'question_status' => [
        'draft' => 0,
        'register' => 1
    ],

    'reserve_type_code' => [
        'public' => 1,
        'update' => 2,
        'delete' => 3
    ],

    'ask_type' =>[
        1 => [
           'title'=> '操作方法がわからない',
            'keyword'=> ['操作方法', 'わからない']
        ],
        2 => [
            'title'=> '用語がわからない',
            'keyword'=> ['用語', 'わからない']
        ],
        3 => [
            'title'=> 'トラブルが起こった',
            'keyword'=> ['トラブル']
        ],
    ],

    'week_days'=>['日', '月', '火', '水', '木', '金', '土'],

    'user_type' => [
        'senpai' => 1,
        'kouhai' => 0
    ],
    'user_type_label' => [
        1 => 'センパイ',
        0 => 'コウハイ'
    ],
    'staff_type' => [
        'kouhai' => 0,
        'senpai' => 1
    ],
    'msg_state' => [
        'unread' => 0,
        'read' => 1
    ],
    'coupon_state' => [
        'valid' => 0,
        'used' => 1,
        'old' => 2
    ],
    'coupon_sort' => [
        'period_short' => 0,
        'period_long' => 1,
        'money_large' => 2,
        'money_small' => 3,
        'condition_large' => 4,
        'condition_small' => 5
    ],
    'account_type' => [
        'normal' => 0,
        'current' => 1,
        'store' => 2
    ],
    'bnk_fav' => [
        'normal' => 0,
        'favourite' => 1
    ],
    'alphabet' => [
        'あ', 'い', 'う', 'え', 'お',
        'か', 'き', 'く', 'け', 'こ',
        'さ', 'し', 'す', 'せ', 'そ',
        'た', 'ち', 'つ', 'て', 'と',
        'な', 'に', 'ぬ', 'ね', 'の',
        'は', 'ひ', 'ふ', 'へ', 'ほ',
        'ま', 'み', 'む', 'め', 'も',
        'や', 'ゆ', 'よ',
        'わ'
    ],
    'alphabet_code' => [
        'あ'=>'ｱ', 'い'=>'ｲ', 'う'=>'ｳ', 'え'=>'ｴ', 'お'=>'ｵ',
        'か'=>'ｶ,ｶﾞ', 'き'=>'ｷ,ｷﾞ', 'く'=>'ｸ,ｸﾞ', 'け'=>'ｹ,ｹﾞ', 'こ'=>'ｺ,ｺﾞ',
        'さ'=>'ｻ,ｻﾞ', 'し'=>'ｼ,ｼﾞ', 'す'=>'ｽ,ｽﾞ', 'せ'=>'ｾ,ｾﾞ', 'そ'=>'ｿ,ｿﾞ',
        'た'=>'ﾀ,ﾀﾞ', 'ち'=>'ﾁ,ﾁﾞ', 'つ'=>'ﾂ,ﾂﾞ', 'て'=>'ﾃ,ﾃﾞ', 'と'=>'ﾄ,ﾄﾞ',
        'な'=>'ﾅ', 'に'=>'ﾆ', 'ぬ'=>'ﾇ', 'ね'=>'ﾈ', 'の'=>'ﾉ',
        'は'=>'ﾊ,ﾊﾞ,ﾊﾟ', 'ひ'=>'ﾋ,ﾋﾞ,ﾋﾟ', 'ふ'=>'ﾌ,ﾌﾞ,ﾌﾟ', 'へ'=>'ﾍ,ﾍﾞ,ﾍﾟ', 'ほ'=>'ﾎ,ﾎﾞ,ﾎﾟ',
        'ま'=>'ﾏ', 'み'=>'ﾐ', 'む'=>'ﾑ', 'め'=>'ﾒ', 'も'=>'ﾓ',
        'や'=>'ﾔ', 'ゆ'=>'ﾕ', 'よ'=>'ﾖ',
        'わ'=>'ﾜ'
    ],

    'change_position_type' => [
        'no_change' => 0,
        'lesson_position' => 1,
        'set_position' => 2
    ],

    'no_confirm' => [
        'cancel_request' => 0,
        'old_request' => 1
    ],

    'favourite_type' => [
        'lesson' => 0,
        'follow' => 1,
        'follower' => 2
    ],

    'login_mode' => [
        'email' => 0,
        'phone' => 1,
    ],

    'talkroom_state' => [
        'talking' => 0,
        'finished'=> 1
    ],

    'load_state' => [
        'unable' => 0,
        'able'=> 1,
        'full' => 2
    ],

    'area_deep_code' => [
        'region' => 1,
        'pref' => 2,
        'city' => 3
    ],

    'page_type' => [
        'keijibann' => 'keijibann',
        'lesson' => 'lesson'
    ],

    'payment_methods' => [
        1 => 'クレジットカード',
        2 => '電子マネー',
        3 => '後払い決済',
    ],

    'payment_methods_code' => [
        'card' => 1,
        'e_money' => 2,
        'after' => 3
    ],

    'emoney_methods' => [
        1 => 'Pay Pay',
        2 => 'LINE Pay'
    ],

    'emoney_methods_code' => [
        'pay_pay' => 1,
        'line_pay' => 2
    ],

    'user_sort' => [
        1 => '登録が新しい順',
        2 => '登録が古い順',
    ],
    'user_sort_code' => [
        'register_new' => 1,
        'register_old' => 2,
    ],

    'report_sort' => [
        1 => '古い順',
        2 => '新しい順',
        3 => '回数の多い順',
    ],
    'report_sort_code' => [
        'register_old' => 1,
        'register_new' => 2,
        'report_count' => 3,
    ],

    'stop_lesson_sort' => [
        1 => '新しい順',
        2 => '古い順',
    ],
    'stop_lesson_sort_code' => [
        'register_new' => 1,
        'register_old' => 2,
    ],

    'bank_account_type' => [
        1 => '普通預金',
        2 => '当座預金',
        3 => '貯蓄預金',
    ],

    'date_type' => [
        'day' => 1,
        'month' => 2,
        'year' => 3,
        'period' => 4,
    ],

    'transfer_application_fee' => 200,
    'transfer_status' => [
        'apply' => 1,
        'sent' => 2
    ],
    'transfer_config' => [
        'application_end_date' => 26, // 振込予定日
        'application_send_date' => 5, // 振込予定日
        'possible_send_date' => 5 // 来月XX日以降の申請可能な売上
    ],
    'company_info' => [
        'zip' => '〒550-0002',
        'address1' => '大阪府大阪市西区',
        'address2' => '江戸堀1丁目26-24-1005',
        'name' => '株式会社ｉｆｉｆ'
    ],
    'hirosikimaru_show' => [
        1 => '適用する',
        2 => '適用しない'
    ],
    'hirosikimaru_show_code' => [
        'apply' => 1,
        'no_apply' => 2
    ],
    'modal_type'=> [
        'confirm' => 1,
        'alert' => 2
    ],
    'inquiry_period' => [
        1=>'1ヵ月以内',
        3=>'3ヵ月以内',
        6=>'6ヵ月以内',
        12=>'1年以内',
    ],
    'appeal_class_color' => [
        1=>"rgb(0, 88, 255)",
        2=>"rgb(33, 213, 155)",
        3=>"rgb(255, 199, 0)",
        4=>"rgb(249, 150, 0)",
        5=>"rgb(234, 123, 170)"
    ],
    'punishment_decision' => [
        1 => '厳重主義',
        2 => 'レッスン出品・掲示板の投稿を停止',
        3 => '売買・投稿停止',
        4 => 'アカウント凍結'
    ],
    'punishment_decision_code' => [
        'caution' => 1,
        'lesson_article_stop' => 2,
        'buy_sell_stop' => 3,
        'account_freeze' => 4
    ],
    'basis' => [
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => 'その他'
    ],
    'basis_code' => [
        'first' => 1,
        'second' => 2,
        'third' => 3,
        'forth' => 4,
        'other' => 5
    ],
    'stop_period' => [
        1 => 7,
        2 => 30,
        3 => 60,
        4 => 90
    ],
    'lesson_stop' => [
        0 => '',
        1 => '公開停止',
        2 => 'レッスンを中断',
    ],
    'lesson_stop_code' => [
        'no_stop_lesson' => 0,
        'stop_lesson' => 1,
        'break_lesson' => 2,
    ],
    'punishment_show' => [
        1 => '適用しない',
        2 => '適用する'
    ],
    'punishment_show_code' => [
        'no_apply' => 1,
        'apply' => 2,
    ],
    // レッスンの公開停止取り消方法
    'stop_lesson_cancel' => [
        'now' => 1,
        'reserve' => 2,
    ],

    'person_confirm_browser' => [
        'new' => 1,
        'change' => 2,
    ],
    'lesson_service_browser' => [
        'new' => 1,
        'change' => 2,
    ],
    'person_confirm_agree_category' => [
        'agree' => 1,
        'disagree' => 2,
    ],
    'agree_flag' => [
        'agree' => 1,
        'disagree' => 2,
    ],
    'person_confirm_type' => [
        1 => "運転免許証／運転経歴証明書",
        2 => "健康保険証",
        3 => "マイナンバーカード",
        4 => "学生証",
        5 => "パスポート",
        6 => "住民票"
    ],
    'person_confirm_type_code' => [
        'driver_license' => 1,
        'insurance_card' => 2,
        'number_card' => 3,
        'student_card' => 4,
        'passport' => 5,
        'resident_card' => 6
    ],
    'person_confirm_agree_type' => [
        1 => "書面の名称は正しいですか？",
        2 => "発行元の名称は正しいですか？",
        3 => "氏名は正しいですか？",
        4 => "生年月日は正しいですか？",
        5 => "有効期限は正しいですか？"
    ],
    'person_confirm_disagree_type' => [
        1 => "選択された本人確認書面とご提出いただいた書面の種類が一致しない。",
        2 => "ご提出いただいた書面の発行元の名前が正しくない。",
        3 => "入力された氏名とご提出頂いた書面の氏名が一致していない。",
        4 => "入力された生年月日とご提出頂いた書面の生年月日が一致していない。",
        5 => "ご提出頂いた書面の有効期限が切れている。",
        6 => "画像がブレている。",
        7 => "必要事項が写っていない。"
    ],
    'read_status' => [
        'not_read' => 0,
        'read' => 1
    ],
    'age_year' => [
        'tenth' => 10,
        'twentieth' => 20,
        'thirtieth' => 30,
        'fortieth' => 40,
        'fiftieth' => 50,
        'sixtieth' => 60,
        'seventieth' => 70
    ],
    'average_evalution' => [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5
    ],
    'average_evalution_period' => [
        1 => [
            'min' => 0,
            'max' => 1.45
        ],
        2 => [
            'min' => 1.45,
            'max' => 2.45
        ],
        3 => [
            'min' => 2.45,
            'max' => 3.45
        ],
        4 => [
            'min' => 3.45,
            'max' => 4.45
        ],
        5 => [
            'min' => 4.45,
            'max' => 5
        ]
    ],
    'lesson_history_status' => [
        1 => '終了レッスンのみ表示',
        2 => '予約中のみ表示',
        3 => '予約キャンセル済のみ表示',
        4 => '予約リクエスト済のみ表示',
        5 => '出動リクエスト済のみ表示',
        6 => '予約リクエスト取消し済のみ表示',
        7 => '出動リクエスト取消し済のみ表示'
    ],
    'lesson_history_status_code' => [
        'complete' => 1,
        'reserve_suggest' => 2,
        'reserve_cancel_complete' => 3,
        'reserve_complete' => 4,
        'attendance_complete' => 5,
        'reserve_canceled' => 6,
        'attendance_canceled' => 7
    ],
    'recruit_history_status' => [
        1 => '終了レッスンのみ表示',
        2 => '予約中のみ表示',
        3 => '予約キャンセル済のみ表示',
        4 => '提案有のみ表示',
        5 => '予約不成立のみ表示'
    ],
    'recruit_history_status_code' => [
        'complete' => 1,
        'reserve_suggest' => 2,
        'reserve_cancel_complete' => 3,
        'has_suggest' => 4,
        'reserve_not_success' => 5
    ],
    'question_frequent' => 1,
    'lesson_op_type'=>[
        1 => 'create',
        2 => 'update'
    ],
    'lesson_op_type_code'=>[
        'new' => 1,
        'change' => 2
    ],
    'lesson_request_status' => [
        1 => '予約リクエスト済のみ表示',
        2 => '出動リクエスト済のみ表示',
        3 => '予約リクエスト回答済のみ表示',
        4 => '出動リクエスト回答済のみ表示',
        5 => '予約中のみ表示',
        6 => '終了レッスンのみ表示',
        7 => 'キャンセル済のみ表示',
    ],
    'lesson_request_status_code' => [
        'reserve_request' => 1,
        'attendance_request' => 2,
        'reserve_response' => 3,
        'attendance_response' => 4,
        'reserve' => 5,
        'complete' => 6,
        'cancel' => 7
    ],

    'main_visuals' => [
        'kouhai_guid' => 'ご利用ガイド（コウハイ向け）',
        'senpai_guid' => 'ご利用ガイド（センパイ向け）',
        'lesson_start_guid' => 'レッスンのはじめかた',
        'lesson_release_guid' => '出品はじめかたガイド',
        'keijiban_recruit_guid' => '掲示板ガイド(募集)',
        'keijiban_propose_guid' => '掲示板ガイド(提案)',
    ],

    'text_contents' => [
        'invite_friend' => '友達招待',
        'kouhai_protocol' => '後輩会員利用規約',
        'senpai_protocol' => '先輩会員利用規約',
        'privacy' => 'プライバシーポリシー',
        'special_market' => '特定商取引法に基づく表記',
        'payment' => '資金決済法に基づく表記',
    ],

    'maintenance_services' => [
        1 => '掲示板機能',
        2 => '新規出品機能',
        3 => '新規リクエスト機能',
        4 => '新規予約購入機能',
        5 => '出金機能',
        6 => '該当期間レッスン実施機能',
        7 => 'よくある質問機能',
        8 => '新規登録機能',
        9 => '本人確認機能',
        10 => '新規クーポン発行機能',
        11 => '新規クーポン使用機能',
        12 => 'サービス全般',
    ],
    'news_category' => [
        1 => 'なし',
        2 => 'お願い',
        3 => '重要',
        4 => '警告',
    ],

    'news_status' => [
        0 => '下書き',
        1 => '今すぐ公開',
        2 => '日時を定めて公開',
        3 => '今すぐ非公開',
        4 => '日時を定めて非公開',
    ],
    'news_status_code' => [
        'draft' => 0,
        'publish' => 1,
        'limit_publish' => 2,
        'n_publish' => 3,
        'limit_n_publish' => 4
    ]

];

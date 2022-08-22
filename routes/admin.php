<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');

Route::post('login', 'Auth\LoginController@login');

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/', 'StaffController@index')->name('admin.home');
    Route::get('/top', 'TopController@index')->name('admin.top.index');

    Route::group(['prefix' => 'staff'], function () {
        Route::get('/index', 'StaffController@index')->name('admin.staff.index');
        Route::get('detail/{staff?}', 'StaffController@profile')->name('admin.staff.detail');
        Route::post('caution', 'StaffController@caution')->name('admin.staff.caution');
    });

    Route::group(['prefix' => 'staff_confirm'], function () {
        Route::get('/', 'StaffConfirmController@index')->name('admin.staff_confirm.index');
        Route::get('/detail/{person_confirm?}', 'StaffConfirmController@detail')->name('admin.staff_confirm.detail');
        Route::post('/alert_create', 'StaffConfirmController@doCreateAlert')->name('admin.staff_confirm.do_alert_create');
        Route::get('/alert_create', 'StaffConfirmController@createAlert')->name('admin.staff_confirm.alert_create');
        Route::post('/alert_confirm', 'StaffConfirmController@doConfirmAlert')->name('admin.staff_confirm.do_alert_confirm');
        Route::get('/alert_confirm', 'StaffConfirmController@confirmAlert')->name('admin.staff_confirm.alert_confirm');
        Route::get('/send_alert', 'StaffConfirmController@sendAlert')->name('admin.staff_confirm.send_alert');
    });

    Route::group(['prefix' => 'fraud'], function () {
        // 通報
        Route::group(['prefix' => 'report'], function () {
            Route::get('/', 'FraudReportController@index')->name('admin.fraud_report.index');
            Route::get('detail/{user?}', 'FraudReportController@detail')->name('admin.fraud_report.detail');
            Route::post('/get_detail', 'FraudReportController@getDetail')->name('admin.fraud_report.get_detail');
            Route::post('/set_not_read', 'FraudReportController@doSetNotRead')->name('admin.fraud_report.set_not_read');
        });

        // ブロック一覧
        Route::group(['prefix' => 'block'], function () {
            Route::get('/', 'FraudBlockController@index')->name('admin.fraud_block.index');
            Route::get('detail/{user?}', 'FraudBlockController@detail')->name('admin.fraud_block.detail');
            Route::post('/set_not_read', 'FraudBlockController@doSetNotRead')->name('admin.fraud_block.set_not_read');
        });

        // ぴろしきまる履
        Route::group(['prefix' => 'piro'], function () {
            Route::get('/', 'FraudPiroController@index')->name('admin.fraud_piro.index');
            Route::get('detail/{punishment?}', 'FraudPiroController@detail')->name('admin.fraud_piro.detail');
            Route::get('create/{user}', 'FraudPiroController@create')->name('admin.fraud_piro.create');
            Route::get('create_alert', 'FraudPiroController@createAlert')->name('admin.fraud_piro.create_alert');
            Route::post('create_alert', 'FraudPiroController@doCreateAlert')->name('admin.fraud_piro.create_alert_post');
            Route::get('confirm', 'FraudPiroController@confirm')->name('admin.fraud_piro.confirm');
            Route::post('confirm', 'FraudPiroController@doConfirm')->name('admin.fraud_piro.confirm_post');
            Route::post('register_alert', 'FraudPiroController@registerAlert')->name('admin.fraud_piro.register_alert');
        });

        // 公開停止レッスン
        Route::group(['prefix' => 'stop_lesson'], function () {
            Route::get('/', 'FraudStopLessonController@index')->name('admin.fraud_stop_lesson.index');
            Route::get('/detail/{lesson?}', 'FraudStopLessonController@detail')->name('admin.fraud_stop_lesson.detail');
            Route::get('/cancel/{lesson_id?}', 'FraudStopLessonController@cancel')->name('admin.fraud_stop_lesson.cancel');
            Route::post('/do_cancel', 'FraudStopLessonController@doCancel')->name('admin.fraud_stop_lesson.do_cancel');
            Route::get('/search', 'FraudStopLessonController@search')->name('admin.fraud_stop_lesson.search');
            Route::post('/search', 'FraudStopLessonController@doSearch')->name('admin.fraud_stop_lesson.do_search');
        });

        // 公開停止投稿
        Route::group(['prefix' => 'stop_recruit'], function () {
            Route::get('/', 'FraudStopRecruitController@index')->name('admin.fraud_stop_recruit.index');
            Route::get('/detail/{recruit?}', 'FraudStopRecruitController@detail')->name('admin.fraud_stop_recruit.detail');
            Route::get('/cancel/{recruit_id?}', 'FraudStopRecruitController@cancel')->name('admin.fraud_stop_recruit.cancel');
            Route::post('/do_cancel', 'FraudStopRecruitController@doCancel')->name('admin.fraud_stop_recruit.do_cancel');
        });

        // 取り消し予約一覧
        Route::group(['prefix' => 'cancel_reserve'], function () {
            Route::get('/lesson', 'FraudCancelReserveController@lesson')->name('admin.fraud_cancel_reserve.lesson');
            Route::get('/lesson_detail/{lesson?}', 'FraudCancelReserveController@lessonDetail')->name('admin.fraud_cancel_reserve.lesson_detail');
            Route::post('/lesson_delete', 'FraudCancelReserveController@lessonDelete')->name('admin.fraud_cancel_reserve.lesson_delete');
            Route::get('/recruit', 'FraudCancelReserveController@recruit')->name('admin.fraud_cancel_reserve.recruit');
            Route::get('/recruit_detail/{recruit?}', 'FraudCancelReserveController@recruitDetail')->name('admin.fraud_cancel_reserve.recruit_detail');
            Route::post('/recruit_delete', 'FraudCancelReserveController@recruitDetete')->name('admin.fraud_cancel_reserve.recruit_delete');
        });
    });

    // お問い合わせ
    Route::group(['prefix' => 'inquiry'], function () {
        Route::get('/', 'InquiryController@index')->name('admin.inquiry.index');
        Route::get('/detail', 'InquiryController@detail')->name('admin.inquiry.detail');
    });

    // 出品審査
    Route::group(['prefix' => 'lesson_examination'], function () {
        Route::get('/', 'LessonExaminationController@index')->name('admin.lesson_examination.index');
        Route::get('/detail/{lesson_id}', 'LessonExaminationController@detail')->name('admin.lesson_examination.detail');
        Route::get('/agree', 'LessonExaminationController@agree')->name('admin.lesson_examination.agree');
        Route::get('/disagree/{lesson?}', 'LessonExaminationController@disagree')->name('admin.lesson_examination.disagree');
        Route::post('/post_reason', 'LessonExaminationController@postReason')->name('admin.lesson_examination.post_reason');

        Route::get('/alert_create', 'LessonExaminationController@createAlert')->name('admin.lesson_examination.alert_create');
        Route::post('/alert_confirm', 'LessonExaminationController@doConfirmAlert')->name('admin.lesson_examination.do_alert_confirm');
        Route::get('/alert_confirm', 'LessonExaminationController@confirmAlert')->name('admin.lesson_examination.alert_confirm');
        Route::get('/send_alert', 'LessonExaminationController@sendAlert')->name('admin.lesson_examination.send_alert');
    });

    // レッスン履歴
    Route::group(['prefix' => 'lesson_history'], function () {
        Route::get('/lesson', 'LessonHistoryManagementController@lesson')->name('admin.lesson_history_management.lesson');
        Route::get('/lesson_detail/{lesson_request_schedule?}', 'LessonHistoryManagementController@lessonRequestScheduleDetail')->name('admin.lesson_history_management.lesson_detail');
        Route::get('/recruit', 'LessonHistoryManagementController@recruit')->name('admin.lesson_history_management.recruit');
        Route::get('/recruit_detail/{recruit?}', 'LessonHistoryManagementController@recruitDetail')->name('admin.lesson_history_management.recruit_detail');
        Route::get('/{lesson_request_schedule?}/alert_create', 'LessonHistoryManagementController@createAlert')->name('admin.lesson_history_management.alert_create');
        Route::get('/{recruit?}/alert_recruit_create', 'LessonHistoryManagementController@createRecruitAlert')->name('admin.lesson_history_management.alert_recruit_create');
        Route::post('/alert_confirm', 'LessonHistoryManagementController@doConfirmAlert')->name('admin.lesson_history_management.do_alert_confirm');
    });

    // パトロール
    Route::group(['prefix' => 'patrol'], function () {

        // ホーム
        Route::get('/', 'PatrolManagementController@index')->name('admin.patrol.index');

        // 掲示板
        Route::get('/recruit', 'PatrolManagementController@recruit')->name('admin.patrol.recruit');
        Route::post('/area_modal', 'PatrolManagementController@areaModal')->name('admin.area_modal');
        Route::get('/recruit_detail/{recruit?}', 'PatrolManagementController@recruitDetail')->name('admin.patrol.recruit.detail');
        Route::get('/recruit_proposal/{proposal?}', 'PatrolManagementController@recruitProposal')->name('admin.patrol.recruit.proposal');
        Route::get('/stop_recruit/{recruit?}', 'PatrolManagementController@stopRecruit')->name('admin.patrol.stop_recruit');

        // リクエスト管理
        Route::get('/request_send', 'PatrolManagementController@requestSend')->name('admin.patrol.request_send');
        Route::get('/request_send_detail/{lessonRequest?}', 'PatrolManagementController@requestSendDetail')->name('admin.patrol.request_send_detail');
        Route::get('/request_answer', 'PatrolManagementController@requestAnswer')->name('admin.patrol.request_answer');
        Route::get('/request_answer_detail/{lessonRequest?}', 'PatrolManagementController@requestAnswerDetail')->name('admin.patrol.request_answer_detail');
        Route::get('/{lessonRequest?}/alert_create', 'PatrolManagementController@createAlert')->name('admin.patrol.alert_create');
        Route::post('/request_delete', 'PatrolManagementController@requestDelete')->name('admin.patrol.request_delete');

    });

    // よくある質問
    Route::group(['prefix' => 'freq'], function () {
        Route::get('/', 'FreqQuestionController@index')->name('admin.freq.index');

        // ajax
        Route::post('/get_sub_category', 'FreqCategoryController@getSubCategoryAjax')->name('admin.freq.get_sub_category');
        Route::post('/get_question_ajax', 'FreqQuestionController@getQuestionAjax')->name('admin.freq.get_question_ajax');

        // よくある質問一覧
        Route::group(['prefix' => 'question'], function () {
            Route::get('/', 'FreqQuestionController@question')->name('admin.freq.question');
            Route::get('/{question?}/detail', 'FreqQuestionController@questionDetail')->name('admin.freq.question.detail');
            Route::post('/set_no_public', 'FreqQuestionController@doQuestionNoPublic')->name('admin.freq.question.set_no_public');
            Route::get('/{question?}/edit', 'FreqQuestionController@questionEdit')->name('admin.freq.question.edit');
            Route::post('/edit', 'FreqQuestionController@doQuestionEdit')->name('admin.freq.question.questionEdit');
            Route::post('/draft', 'FreqQuestionController@draftQuestion')->name('admin.freq.question.draft');
            Route::get('/{question?}/reserve', 'FreqQuestionController@questionReserve')->name('admin.freq.question.reserve_question');
            Route::post('/reserve', 'FreqQuestionController@doQuestionReserve')->name('admin.freq.question.reserve');
            Route::post('/reserve_create', 'FreqQuestionController@reserveQuestionCreate')->name('admin.freq.question.reserve_create');

        });

        // よく見られている質問管理
        Route::group(['prefix' => 'normal_question'], function () {
            Route::get('/', 'FreqQuestionController@normalQuestion')->name('admin.freq.normal_question');
            Route::get('/{frequent_type?}/detail', 'FreqQuestionController@normalQuestionDetail')->name('admin.freq.normal_question.detail');
            Route::get('/{frequent_type?}/add', 'FreqQuestionController@normalQuestionAdd')->name('admin.freq.normal_question.add');
            Route::get('/{frequent_type?}/question_detail/{question?}', 'FreqQuestionController@normalQuestionContent')->name('admin.freq.normal_question.question_detail');
            Route::post('/frequent_add', 'FreqQuestionController@normalQuestionAddFrequent')->name('admin.freq.normal_question.frequent_add');
            Route::get('/{frequent_type?}/{question?}/content_data', 'FreqQuestionController@normalQuestionContentData')->name('admin.freq.normal_question.content_data');
            Route::get('/{frequent_type?}/sort', 'FreqQuestionController@normalQuestionSort')->name('admin.freq.normal_question.sort');
            Route::post('/set_sort', 'FreqQuestionController@setNormalQuestionSort')->name('admin.freq.normal_question.set_sort');
            Route::get('/{frequent_type?}/{question?}/change_content', 'FreqQuestionController@normalQuestionChange')->name('admin.freq.normal_question.change_content');
        });

        // 下書き・非公開一覧

        Route::group(['prefix' => 'no_public_question'], function () {
            Route::get('/', 'FreqQuestionController@noPublicQuestion')->name('admin.freq.no_public_question');
            Route::get('/{question?}/detail', 'FreqQuestionController@noPublicQuestionDetail')->name('admin.freq.no_public_question.detail');
            Route::get('/{question?}/edit', 'FreqQuestionController@noPublicQuestionEdit')->name('admin.freq.no_public_question.edit');
            Route::post('/delete', 'FreqQuestionController@noPublicQuestionDelete')->name('admin.freq.no_public_question.delete');
            Route::get('/{question?}/reserve', 'FreqQuestionController@noPublicQuestionReserve')->name('admin.freq.no_public_question.reserve_question');
        });

        // 各予約一覧
        Route::group(['prefix' => 'reserve_question'], function () {
            Route::get('/', 'FreqQuestionController@reserveInfo')->name('admin.freq.reserve_question');
            Route::get('/{question?}/edit', 'FreqQuestionController@reserveQuestionEdit')->name('admin.freq.reserve_question.edit');
            Route::get('/{question?}/update', 'FreqQuestionController@reserveQuestionUpdate')->name('admin.freq.reserve_question.update');
            Route::post('/update', 'FreqQuestionController@doReserveQuestionUpdate')->name('admin.freq.reserve_question.doReserveQuestionUpdate');
            Route::get('/{question?}/change', 'FreqQuestionController@reserveQuestionDateChange')->name('admin.freq.reserve_question.change_date');
            Route::post('/change', 'FreqQuestionController@doReserveQuestionDateChange')->name('admin.freq.reserve_question.changeDate');
        });

        // よくある質問_新規作成
        Route::get('/new_question', 'FreqQuestionController@newQuestion')->name('admin.freq.new_question');
        Route::post('/new_question/create', 'FreqQuestionController@createNewQuestion')->name('admin.freq.new_question.create');
        Route::post('/new_question/draft', 'FreqQuestionController@draftNewQuestion')->name('admin.freq.new_question.draft');
        Route::get('/new_question/reserve', 'FreqQuestionController@reserveQuestion')->name('admin.freq.new_question.reserve_question');
        Route::post('/new_question/reserve', 'FreqQuestionController@reserveNewQuestion')->name('admin.freq.new_question.reserve');
        Route::post('/new_question/reserve_create', 'FreqQuestionController@reserveNewQuestionCreate')->name('admin.freq.new_question.reserve_create');

        // カテゴリー管理
        Route::group(['prefix' => 'category'], function () {
            // カテゴリー
            Route::get('/', 'FreqCategoryController@index')->name('admin.freq.category.index');
            Route::get('/new', 'FreqCategoryController@newCategory')->name('admin.freq.category.new');
            Route::post('/add_category', 'FreqCategoryController@addCategory')->name('admin.freq.category.add_category');
            Route::get('/delete', 'FreqCategoryController@deleteCategory')->name('admin.freq.category.delete');
            Route::post('/destroy_category', 'FreqCategoryController@destroyCategory')->name('admin.freq.category.destroy_category');
            Route::get('/public_category', 'FreqCategoryController@publicCategory')->name('admin.freq.category.public_category');
            Route::post('/set_public_category', 'FreqCategoryController@setPublicCategory')->name('admin.freq.category.set_public_category');
            Route::get('/sort_category', 'FreqCategoryController@sortCategory')->name('admin.freq.category.sort_category');
            Route::post('/set_sort_category', 'FreqCategoryController@setSortCategory')->name('admin.freq.category.set_sort_category');

            // サブカテゴリー
            Route::get('/sub_category/{category?}', 'FreqCategoryController@subCategory')->name('admin.freq.sub_category.index');
            Route::get('/sub_category/{category?}/new', 'FreqCategoryController@newSubCategory')->name('admin.freq.sub_category.new');
            Route::post('/add_sub_category', 'FreqCategoryController@addSubCategory')->name('admin.freq.sub_category.add_category');
            Route::get('/sub_category/{category?}/delete', 'FreqCategoryController@deleteSubCategory')->name('admin.freq.sub_category.delete');
            Route::post('/destroy_sub_category', 'FreqCategoryController@destroySubCategory')->name('admin.freq.sub_category.destroy_category');
            Route::get('/sub_category/{category?}/public_category', 'FreqCategoryController@publicSubCategory')->name('admin.freq.sub_category.public_category');
            Route::post('/set_public_sub_category', 'FreqCategoryController@setPublicSubCategory')->name('admin.freq.sub_category.set_public_category');
            Route::get('/sub_category/{category?}/sort_category', 'FreqCategoryController@sortSubCategory')->name('admin.freq.sub_category.sort_category');
            Route::post('/set_sort_sub_category', 'FreqCategoryController@setSortSubCategory')->name('admin.freq.sub_category.set_sort_category');

        });
    });

    // マスター管理
    Route::group(['prefix' => 'master'], function () {
        Route::get('/', 'MasterController@index')->name('admin.master.index');
        Route::get('/main_visual/{type}', 'MasterController@mainVisual')->name('admin.master.main_visual');
        Route::post('/save_guid/{type}', 'MasterController@saveGuid')->name('admin.master.save_guid');
        Route::post('/remove_visual_page/{main_visual}', 'MasterController@removeVisualPage')->name('admin.master.remove_visual_page');
        Route::post('/clear_visual/{type}', 'MasterController@clearVisual')->name('admin.master.clear_visual');
        Route::post('/upload_file', 'MasterController@uploadFile')->name('admin.master.upload_file');
        Route::post('/delete_file', 'MasterController@deleteFile')->name('admin.master.delete_file');

        Route::get('/text_master/{type}', 'MasterController@textMaster')->name('admin.master.text_master');
        Route::post('/save_text/{type}', 'MasterController@saveText')->name('admin.master.save_text');

        //ajax アクセス数コストと実施コスト
        Route::post('/set_cost', 'MasterController@setCost')->name('admin.master.set_cost');
        Route::post('/set_reviews', 'MasterController@setReviews')->name('admin.master.set_reviews');
    });

    // メンテナンス管理
    Route::group(['prefix' => 'maintenance'], function () {
        Route::get('/', 'MaintenanceController@index')->name('admin.maintenance.index');
        Route::get('/create', 'MaintenanceController@create')->name('admin.maintenance.create');
        Route::post('/store', 'MaintenanceController@store')->name('admin.maintenance.store');
        Route::get('/edit/{maintenance}', 'MaintenanceController@edit')->name('admin.maintenance.edit');
        Route::post('/update/{maintenance}', 'MaintenanceController@update')->name('admin.maintenance.update');
        Route::post('/confirm', 'MaintenanceController@confirm')->name('admin.maintenance.confirm');
        Route::get('/detail/{maintenance}', 'MaintenanceController@detail')->name('admin.maintenance.detail');
        Route::post('/delete/{maintenance}', 'MaintenanceController@delete')->name('admin.maintenance.delete');
    });

    // ニュース管理
    Route::group(['prefix' => 'news'], function () {
        Route::get('/', 'NewsController@index')->name('admin.news.index');
        Route::get('/news_list', 'NewsController@newsList')->name('admin.news.news_list');
        Route::get('/create', 'NewsController@create')->name('admin.news.create');
        Route::post('/set_info/{news?}', 'NewsController@setInfo')->name('admin.news.set_info');
        Route::get('/to_publish/{news?}', 'NewsController@toPublish')->name('admin.news.to_publish');
        Route::get('/to_private/{news}', 'NewsController@toPrivate')->name('admin.news.to_private');
        Route::post('/store', 'NewsController@store')->name('admin.news.store');
        Route::get('/edit/{news}', 'NewsController@edit')->name('admin.news.edit');
        Route::post('/update/{news}', 'NewsController@update')->name('admin.news.update');
        Route::get('/detail/{news}', 'NewsController@detail')->name('admin.news.detail');

        Route::post('/delete', 'NewsController@delete')->name('admin.news.delete');

        Route::get('/drafts', 'NewsController@drafts')->name('admin.news.drafts');
        Route::get('/reserves', 'NewsController@reserves')->name('admin.news.reserves');

    });

    // ログアウト
    Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout');
});

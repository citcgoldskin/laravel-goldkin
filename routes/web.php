<?php

use Illuminate\Support\Facades\Route;

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

//Route::get('/', function () {
//    return view('welcome');
//});

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', 'HomeController@splash')->name('splash');                       //A-0-0.php
Route::get('/lesson_area', 'HomeController@lesson_area')->name('lesson_area');  //A-0-2.php
Route::get('/select_area', 'HomeController@select_area')->name('select_area');  //A-10_3.php
Route::get('/home/{area_id?}/{province_id?}', 'HomeController@home')->name('home');                       //A-2_3.php
Route::get('/welcome', 'HomeController@index')->name('welcome');                //A-0-1.php
Route::get('/welcome_back', 'HomeController@index_back')->name('welcome.back');                //F-1.php
Route::get('/notice/{mode?}', 'HomeController@notice')->name('user.notice');    //A-4.php
Route::get('/todo/{mode?}', 'HomeController@todo')->name('user.todo');    //A-4_1.php
Route::post('/getnewmsg', 'HomeController@getNewMsgCnt')->name('user.get_new_msg');

// documents ( using_rules, privacy policies, guides... )
Route::get('/rules', 'HomeController@showUsingRules')->name('using_rules');
Route::get('/privacy_policy', 'HomeController@showPrivacyPolicy')->name('privacy_policy');
Route::get('/guide/{type}', 'HomeController@guide')->name('user.guide');               //D-0-1.php,D-0-2.php

// login
Route::group(['prefix' => 'login'], function () {
    Route::get('/myaccount', 'Auth\LoginController@beforeLogin')->name('user.login.myaccount.before');          // E-1
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('user.login.form');                             // F-8_9
    Route::post('/', 'Auth\LoginController@login')->name('user.login');
    // Route::get('/lostpwd', 'Auth\LoginController@showlostPwdForm')->name('user.login.lost_pwd.form');           // F-10
    Route::post('/lostpwd', 'Auth\LoginController@lostPwd')->name('user.login.lost_pwd');
    Route::get('/lostpwd/success', 'Auth\LoginController@lostPwdSuccess')->name('user.login.lost_pwd_success'); // F-11
    Route::post('/logout', 'Auth\LoginController@logout')->name('user.login.logout');
});

Route::group(['prefix' => 'password'], function () {
    Route::get('/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.change');
    Route::post('/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('/sent_mail', 'Auth\ForgotPasswordController@sentMail')->name('password.sent_mail');
    Route::get('/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
});

// register
Route::group(['prefix' => 'register'], function () {
    Route::get('/', 'Auth\RegisterController@showRegEmailForm')->name('user.register.email.form');                          // F-2
    Route::post('/', 'Auth\RegisterController@registerEmail')->name('user.register.email');
    Route::get('/sent_mail', 'Auth\RegisterController@sentMail')->name('user.register.sent_mail');
    Route::get('/mail_info', 'Auth\RegisterController@showUserForm')->name('user.register.mail_info');
    Route::get('/profile', 'Auth\RegisterController@showRegProfileForm')->name('user.register.profile.form');               // F-3_4
    Route::post('/profile', 'Auth\RegisterController@registerProfile')->name('user.register.profile');
    Route::get('/phone', 'Auth\RegisterController@showRegPhoneForm')->name('user.register.phone.form');                     // F-5
    Route::post('/phone', 'Auth\RegisterController@registerPhone')->name('user.register.phone');
    Route::get('/verify_phone/{from?}', 'Auth\RegisterController@showVerifyPhoneForm')->name('user.register.verify_phone.form');    // F-6
    Route::post('/verify_phone', 'Auth\RegisterController@verifyPhone')->name('user.register.verify_phone');
    Route::get('/finish', 'Auth\RegisterController@registerFinishForm')->name('user.register.finish');                      // F-7
});

//lesson
Route::group(['prefix' => 'lesson'], function () {
    Route::get('/search/{class_id}/{province_id?}', 'LessonController@search')->name('user.lesson.search');    //A-8.php
    Route::get('/search_condition/{lesson_count}/{province_id?}', 'LessonController@searchCondition')->name('user.lesson.search_condition');    //A-9.php
    Route::post('/set_main_search', 'LessonController@setMainSearch')->name('user.lesson.set_main_search');
    Route::post('/set_search_order', 'LessonController@setSearchOrder')->name('user.lesson.set_search_order');
    Route::get('/area/{province_id?}', 'LessonController@area')->name('user.lesson.area');    //A-10_1.php
    Route::get('/province/{prev_url_id}/{lesson_count?}', 'LessonController@province')->name('user.lesson.province');    //A-10.php
    Route::post('/set_area', 'LessonController@setArea')->name('user.lesson.set_area');
    Route::get('/lesson_view/{lesson_id}', 'LessonController@lessonView')->name('user.lesson.lesson_view');
    Route::get('/detail/{lesson_id}', 'LessonController@detail')->name('user.lesson.detail');    //A-15_16_17.php
    Route::post('/get_week_schedule', 'LessonController@getWeekSchedule')->name('user.lesson.get_week_schedule');
    Route::get('/self_check/{lesson_id}', 'LessonController@selfCheck')->name('user.lesson.self_check');    //A-17-1.php
    Route::get('/setting_reserve_request/{lesson_id?}/{lr_id?}', 'LessonController@settingReserveRequest')->name('user.lesson.setting_reserve_request');    //A-18_19.php
    Route::post('/get_schedule', 'LessonController@ajaxGetSchedule')->name('user.lesson.get_schedule');
    Route::post('/add_reserve_request', 'LessonController@addReserveRequest')->name('user.lesson.add_reserve_request');
    Route::get('/setting_attend_request/{lesson_id}', 'LessonController@settingAttendRequest')->name('user.lesson.setting_attend_request');    //A-22.php
    Route::post('/add_attend_request', 'LessonController@addAttendRequest')->name('user.lesson.add_attend_request');
    /*Route::get('/check_reserve/{lrs_id}', 'LessonController@checkReserve')->name('user.lesson.check_reserve');    //A-24.php*/
    Route::get('/check_reserve/{lr_id}', 'LessonController@checkReserve')->name('user.lesson.check_reserve');    //A-24.php
    /*Route::get('/check_reserve_comp/{lrs_id?}/{cup_id?}/{cpu_id?}', 'LessonController@checkReserveComp')->name('user.lesson.check_reserve_comp');    //A-29.php*/
    Route::get('/check_reserve_comp/{lr_id?}/{cup_id?}/{cpu_id?}', 'LessonController@checkReserveComp')->name('user.lesson.check_reserve_comp');    //A-29.php
    Route::get('/select_pay_method', 'LessonController@selectPayMethod')->name('user.lesson.select_pay_method');    //A-26_27.php
    Route::post('/set_pay_method', 'LessonController@setPayMethod')->name('user.lesson.set_pay_method');
    Route::get('/credit_card', 'LessonController@creditCard')->name('user.lesson.credit_card');    //A-28.php
    Route::post('/add_credit_card', 'LessonController@addCreditCard')->name('user.lesson.add_credit_card');

//kh
    Route::get('/change', 'LessonController@change')->name('user.lesson.change');    //change-lesson.php

});
//keijibann 掲示板
Route::group(['prefix' => 'keijibann'], function () {
    //get
    Route::get('/', 'KeijibannController@index')->name('keijibann.list');                      //B-1.php
    Route::get('/condition/{cnt?}/{province_id?}', 'KeijibannController@condition')->name('keijibann.condition');    //B-3.php
    Route::post('/condition_post', 'KeijibannController@postCondition')->name('keijibann.condition_post');
    Route::get('/category/{cnt?}', 'KeijibannController@category')->name('keijibann.category');       //B-4.php
    Route::get('/province/{prev_url_id}', 'KeijibannController@province')->name('keijibann.province');    //A-10.php
    Route::post('/province_modal', 'KeijibannController@provinceModal')->name('keijibann.province_modal');
    Route::post('/area_modal', 'KeijibannController@areaModal')->name('keijibann.area_modal');
    Route::post('/category_post', 'KeijibannController@postCategory')->name('keijibann.category_post');       //B-4.php
    Route::get('/detail/{id?}', 'KeijibannController@detail')->name('keijibann.detail');             //B-2.php
    Route::get('/input', 'KeijibannController@input')->name('keijibann.input');                //B-5_6.php
    Route::post('/confirm', 'KeijibannController@confirm')->name('keijibann.confirm');          //B-7.php
    Route::get('/conf_com', 'KeijibannController@confCom')->name('keijibann.conf_com');       //B-8.php
    Route::get('/recruiting/{mode?}', 'KeijibannController@recruiting')->name('keijibann.recruiting'); //B-9.php
    Route::get('/draft/{mode?}', 'KeijibannController@draft')->name('keijibann.draft');                //B-16.php
    Route::get('/past_contrib/{mode?}', 'KeijibannController@pastContrib')->name('keijibann.past_contrib');   //B-17.php
    Route::get('/recruiting_detail/{id?}', 'KeijibannController@recruitingDetail')->name('keijibann.recruiting_detail');        //B-10.php
    Route::get('/recruiting_conf/{id?}', 'KeijibannController@recruitingConf')->name('keijibann.recruiting_conf');  //B-11.php
    Route::get('/recruit_book_comp/{id?}', 'KeijibannController@recruitBookComp')->name('keijibann.recruit_book_comp');    //A-29.php
    Route::get('/recruiting_input', 'KeijibannController@recruitingInput')->name('keijibann.recruiting_input');    //B-13_14.php
    Route::get('/recruiting_comp', 'KeijibannController@recruitingComp')->name('keijibann.recruiting_comp');    //B-24_3.php
    Route::get('/recruiting_edit/{id?}', 'KeijibannController@recruitingEdit')->name('keijibann.recruiting_edit');    //B-15.php
    Route::get('/recruiting_edit_comp', 'KeijibannController@recruitingEditComp')->name('keijibann.recruiting_edit_comp');    //B-24_1.php
    Route::get('/recruiting_del_comp', 'KeijibannController@recruitingDelComp')->name('keijibann.recruiting_del_comp');    //B-24_2.php
    Route::get('/recruiting_content/{id?}', 'KeijibannController@recruitingContent')->name('keijibann.recruiting_content');    //B-18.php
    Route::get('/recruiting_proposal/{mode?}', 'KeijibannController@recruitingProposal')->name('keijibann.recruiting_proposal');    //B-19.php
    Route::get('/recruiting_prop_detail/{id?}', 'KeijibannController@recruitingPropDetail')->name('keijibann.recruiting_prop_detail');    //B-20.php
    Route::get('/recruiting_prop_edit/{id?}', 'KeijibannController@recruitingPropEdit')->name('keijibann.recruiting_prop_edit');    //B-26.php
    Route::get('/recruiting_prop_del/{id?}', 'KeijibannController@recruitingPropDel')->name('keijibann.recruiting_prop_del');    //B-24_2b.php
    Route::get('/fee', 'KeijibannController@fee')->name('keijibann.fee');    //B-21.php
    Route::get('/area/{province_id?}', 'KeijibannController@area')->name('keijibann.area');    //A-10_2.php
    Route::post('/set_area', 'KeijibannController@setArea')->name('keijibann.set_area');

    //post
    Route::post('/recruit_input_post', 'KeijibannController@postRecruitInput')->name('keijibann.postRecruitInput');
    Route::post("/class_icon", 'KeijibannController@postClassIcon')->name('keijibann.postClassIcon');
    Route::post("/recruit_vote", 'KeijibannController@postRecruitVote')->name('keijibann.postRecruitVote');
    Route::post("/get_area_2", 'KeijibannController@postGetArea2')->name('keijibann.postGetArea2');
    Route::post("/set_recruit_order", 'KeijibannController@postSetRecruitOrder')->name('keijibann.postSetRecruitOrder');
});


//syutupinn　出品
Route::group(['prefix' => 'syutupinn'], function () {
    Route::get('/', 'SyutupinnController@lessonList')->name('user.syutupinn.lesson_list');                      //C-3_4.php
    Route::get('/ajax_list/{state?}', 'SyutupinnController@getAjaxList')->name('user.syutupinn.ajax_list');
    Route::get('/regLesson/{type?}/{lesson_id?}', 'SyutupinnController@regLesson')->name('user.syutupinn.regLesson');                      //C-13.php, C-8_10.php, C-12.php, C-12_2.php
    Route::get('/online_add_confirm', 'SyutupinnController@onlineAddConfirm')->name('user.syutupinn.online_add_confirm');                      //C-10-3.php
    Route::get('/schedule', 'SyutupinnController@schedule')->name('user.syutupinn.schedule');                      //C-16.php
    Route::post('/save_schedule', 'SyutupinnController@saveSchedule')->name('user.syutupinn.save_schedule');                      //C-19.php

    Route::get('/kouhai_request/{type?}', 'SyutupinnController@requestList')->name('user.syutupinn.request');                      //C-20.php
    Route::get('/ajax_req_list/{type?}', 'SyutupinnController@getAjaxRequestList')->name('user.syutupinn.ajax_req_list');
    Route::get('/reserve_check/{lr_id?}', 'SyutupinnController@reserveCheck')->name('user.syutupinn.reserve_check');                      //C-22_23.php
    Route::get('/attend_check/{lr_id?}', 'SyutupinnController@attendCheck')->name('user.syutupinn.attend_check');                      //C-26.php

    Route::get('/manual', 'SyutupinnController@manual')->name('user.syutupinn.manual');                      //C-5_7.php

    //post
    Route::post('/save_lesson', 'SyutupinnController@saveLesson')->name('user.syutupinn.save_lesson');
    Route::post('/del_lesson', 'SyutupinnController@delLesson')->name('user.syutupinn.del_lesson');
    Route::post('/get_schedule', 'SyutupinnController@getSchedule')->name('user.syutupinn.get_schedule');
    Route::post('/get_week_schedule', 'SyutupinnController@getWeekSchedule')->name('user.syutupinn.get_week_schedule');
    Route::post('/attend_req_save', 'SyutupinnController@attendReqSave')->name('user.syutupinn.attend_req_save');
    Route::post('/reserve_req_save', 'SyutupinnController@reserveReqSave')->name('user.syutupinn.reserve_req_save');
});
//talkroom
Route::group(['prefix' => 'talkroom'], function () {
    Route::get('/list/{type?}', 'TalkroomController@list')->name('user.talkroom.list');                                     //D-1.php
    Route::get('/subscriptionCal/{ym}', 'TalkroomController@subscriptionCal')->name('user.talkroom.subscriptionCal'); //D-2.php
    Route::get('/subscriptionLesson/{menu_type}/{schedule_id}', 'TalkroomController@subscriptionLesson')->name('user.talkroom.subscriptionLesson');//D-3.php, D-4.php
    Route::get('/talkData/{menu_type}/{room_id}', 'TalkroomController@talkData')->name('user.talkroom.talkData');                        //D-5_7.php, D-6_8.php
    Route::get('/requestResp/{old_schedule_id}/{new_schedule_id}', 'TalkroomController@requestResp')->name('user.talkroom.requestResp');               //C-22_23-after.php
    Route::post('/respComplete', 'TalkroomController@respComplete')->name('user.talkroom.respComplete');            //C-22_23-after2.php
    Route::get('/setting/{type}/{from_user_id}', 'TalkroomController@setting')->name('user.talkroom.setting');                              //D-13_14.php
    Route::get('/appeal/{type}/{from_user_id}', 'TalkroomController@appeal')->name('user.talkroom.appeal');         //D-16.php
    Route::get('/serviceEval', 'TalkroomController@serviceEval')->name('user.talkroom.serviceEval');               //D-21_22.php
    Route::get('/kouhaiEval', 'TalkroomController@kouhaiEval')->name('user.talkroom.kouhaiEval');                  //D-21_22b.php
    Route::post('/evalPost', 'TalkroomController@postEval')->name('user.talkroom.evalPost');
    Route::get('/pos_info/{lrs_id?}', 'TalkroomController@pos_info')->name('user.talkroom.pos_info');                           //D-26.php
    Route::get('/requestConfirm/{request_id}', 'TalkroomController@requestConfirm')->name('user.talkroom.requestConfirm');      //D-29_30.php
    Route::get('/requestEdit/{req_id}', 'TalkroomController@requestEdit')->name('user.talkroom.requestEdit');               //D-31.php
    Route::post('/requestEdit', 'TalkroomController@updateRequest')->name('user.talkroom.updateRequest');
    Route::get('/requestCancel/{req_id}', 'TalkroomController@requestCancel')->name('user.talkroom.requestCancel');         //D-33.php
    Route::post('/cancelConfirm', 'TalkroomController@cancelConfirm')->name('user.talkroom.cancelConfirm');         //D-34.php
    Route::post('/cancelSchedules', 'TalkroomController@cancelSchedules')->name('user.talkroom.cancelSchedules');
    Route::get('/cancelAbout', 'TalkroomController@cancelAbout')->name('user.talkroom.cancelAbout');               //D-36_38.php
    Route::post('/sendMessage', 'TalkroomController@sendMessage')->name('user.talkroom.sendMessage');
    Route::post('/getMessages', 'TalkroomController@getMessages')->name('user.talkroom.getMessages');
    Route::post('/getScheduleInfo', 'TalkroomController@getScheduleInfo')->name('user.talkroom.getScheduleInfo');
    Route::post('/setInformState', 'TalkroomController@setInformState')->name('user.talkroom.setInformState');
    Route::post('/setBlockState', 'TalkroomController@setBlockState')->name('user.talkroom.setBlockState');
    Route::post('/sendAppeals', 'TalkroomController@sendAppeals')->name('user.talkroom.sendAppeals');
    Route::post('/clickStartBtn', 'TalkroomController@clickStartBtn')->name('user.talkroom.clickStartBtn');
    Route::post('/get_map_location', 'TalkroomController@getMapLocation')->name('user.talkroom.get_map_location');
    Route::post('/set_share_location', 'TalkroomController@setShareLocation')->name('user.talkroom.set_share_location');
    Route::post('/lesson_cancel_by_position_share', 'TalkroomController@lessonCancelByPositionShare')->name('user.talkroom.lesson_cancel_by_position_share');
});
//my account
Route::group(['prefix' => 'myaccount'], function () {
    //rcr
    Route::get('/', 'MyAccountController@index')->name('user.myaccount.index');                                                 // E-2_3, E-4
    Route::get('/profile/{user_id}', 'MyAccountController@profile')->name('user.myaccount.profile');    //A-14.php
    Route::get('/editprofile', 'MyAccountController@showEditProfileForm')->name('user.myaccount.edit_profile.form');                        // E-5
    Route::post('/editprofile', 'MyAccountController@editProfile')->name('user.myaccount.edit_profile');
    Route::get('/favorite/{type?}', 'MyAccountController@favorite')->name('user.myaccount.favorite');                                               // E-7
    Route::get('/requestmgr/{type?}', 'MyAccountController@requestMgr')->name('user.myaccount.request_mgr');                                // E-8, 9
    Route::get('/paymentmgr', 'MyAccountController@paymentMgr')->name('user.myaccount.payment_mgr');                                        // E-10_11
    Route::get('/payment_kouhai_detail', 'MyAccountController@paymentKouhaiDetail')->name('user.myaccount.payment_kouhai_detail');                                        // alpha-29
    Route::post('/get_payment_with_condition', 'MyAccountController@getPaymentWithCondition')->name('user.myaccount.get_payment_with_condition');
    Route::post('/get_payment_by_year', 'MyAccountController@getPaymentByYear')->name('user.myaccount.get_payment_by_year');
    Route::get('/putmoney', 'MyAccountController@putMoney')->name('user.myaccount.put_money');                                              // E-10_11_furikomi
    Route::post('/apply_transfer_money', 'MyAccountController@applyTransferMoney')->name('user.myaccount.apply_transfer_money');
    Route::get('/putmoneyterm', 'MyAccountController@putMoneyTerm')->name('user.myaccount.put_money_term');                                 // E-12
    Route::get('/paymentdetail', 'MyAccountController@paymentDetail')->name('user.myaccount.payment_detail');                               // E-13_15
    Route::get('/lessonhistory/master', 'MyAccountController@masterLessonHistroy')->name('user.myaccount.master_lesson_history');   // E-16, 22, 24
    Route::get('/lessonrequest/master/{schedule_id}', 'MyAccountController@masterLessonRequest')->name('user.myaccount.master_lesson_request');     // E-17, 23, 25
    Route::get('/cancellesson/master/schedule_id/{schedule_id}', 'MyAccountController@cancelMsterLsn')->name('user.myaccount.cancel_lesson');                        // E-18
    Route::post('/cancellesson/master', 'MyAccountController@cancelMsterLsnSuccess')->name('user.myaccount.cancel_lesson_success');   // E-19
    Route::get('/lessonhistory/student', 'MyAccountController@studentLessonHistory')->name('user.myaccount.student_lesson_history');   // E-26, 31, 33
    Route::get('/lessonrequest/student/{schedule_id}', 'MyAccountController@studentLessonRequest')->name('user.myaccount.student_lesson_detail');   // E-27, 32, 34
    Route::get('/changerequest/step_1/{schedule_id}', 'MyAccountController@changeRequest_1')->name('user.myaccount.changerequest_1');                     // E-27-change-ver
    Route::get('/changerequest/step_2/{schedule_id}', 'MyAccountController@changeRequest_2')->name('user.myaccount.changerequest_2');                     // E-27-change-ver2
    Route::post('/changerequest/update', 'MyAccountController@updateRequest')->name('user.myaccount.update_request');
    Route::get('/changerequest/step_3/{schedule_id}/{old_schedule_id}', 'MyAccountController@changeRequest_3')->name('user.myaccount.changerequest_3');                     // E-27-change-ver3
    Route::get('/cancellesson/student/schedule_id/{schedule_id}', 'MyAccountController@cancelStdntLsn')->name('user.myaccount.cancel_student_lesson');                // E-28
    Route::post('/cancellesson/student/success', 'MyAccountController@cancelStdntLsnSuccess')->name('user.myaccount.cancel_student_lesson_1');   // E-29
    Route::get('/setaccount', 'MyAccountController@setAccount')->name('user.myaccount.set_account');                                        // E-35
    Route::get('/setemailaddress', 'MyAccountController@showChangeEmailForm')->name('user.myaccount.show_email_form');                           // E-36
    Route::post('/setemailaddress', 'MyAccountController@updateEmailAddress')->name('user.myaccount.set_email');
    Route::get('/setpassword', 'MyAccountController@showSetPwdForm')->name('user.myaccount.show_password_form');                                     // E-38
    Route::post('/setpassword', 'MyAccountController@setPassword')->name('user.myaccount.set_password');
    Route::get('/setphone', 'MyAccountController@showPhoneForm')->name('user.myaccount.show_phone');                                              // E-40
    Route::post('/setphone', 'MyAccountController@setPhone')->name('user.myaccount.set_phone');
    Route::post('/verifyphone', 'MyAccountController@verifyPhone')->name('user.myaccount.verify_phone');
    Route::get('/set_user', 'MyAccountController@showSetUserInfo')->name('user.myaccount.set_user.form');                                                // E-41_42
    Route::post('/set_user', 'MyAccountController@setUserInfo')->name('user.myaccount.set_user');
    Route::get('/account/{prev_url_id}', 'MyAccountController@account')->name('user.myaccount.account');                                          // E-44, E-45
    Route::post('/add_account', 'MyAccountController@ajaxAddAccount')->name('user.myaccount.add_account');
    // Route::get('/selbank/{prev_url_id}/{bnk_id}/{act_id}/{act_type_name?}', 'MyAccountController@selBank')->name('user.myaccount.sel_bank');                                                 // E-46_47
    Route::get('/selbank', 'MyAccountController@selBankNew')->name('user.myaccount.sel_bank_new');                                                 // E-46_47
    /*Route::get('/sel_bank_alphabet/{prev_url_id}/{alpha_id}/{prefix}/{act_type_name?}', 'MyAccountController@selBankAlphabet')->name('user.myaccount.sel_bank_alphabet');                       // E-48*/
    Route::get('/sel_bank_alphabet', 'MyAccountController@selBankAlphabet')->name('user.myaccount.sel_bank_alphabet');                       // E-48
    Route::get('/selaccounttype', 'MyAccountController@selAccountType')->name('user.myaccount.sel_account_type');                           // E-49
    Route::get('/edit', 'MyAccountController@edit')->name('user.myaccount.edit_account');                           // E-49

    Route::post('/upload_map_location', 'MyAccountController@uploadMapLocation')->name('user.myaccount.upload_map_location');

    //njh
    Route::get('/block_outline', 'MyAccountController@blockOutline')->name('user.myaccount.block_outline');   // E-51-1
    Route::post('/del_block', 'MyAccountController@ajaxDelBlock')->name('user.myaccount.del_block');
    Route::get('/push_and_mail', 'MyAccountController@pushAndMail')->name('user.myaccount.push_and_mail');   // E-53
    Route::post('/set_push_and_mail', 'MyAccountController@ajaxPushAndMail')->name('user.myaccount.ajax_push_and_mail');
    Route::get('/others', 'MyAccountController@others')->name('user.myaccount.others');   // E-58
    Route::get('/company_abstract', 'MyAccountController@companyAbstract')->name('user.myaccount.company_abstract');
    Route::get('/use_rule', 'MyAccountController@useRule')->name('user.myaccount.use_rule');
    Route::get('/personal_info', 'MyAccountController@personalInfo')->name('user.myaccount.personal_info');
    Route::get('/sale_method', 'MyAccountController@saleMethod')->name('user.myaccount.sale_method');
    Route::get('/pay_method', 'MyAccountController@payMethod')->name('user.myaccount.pay_method');
    Route::get('/sale_detail_list', 'MyAccountController@saleDetailList')->name('user.myaccount.sale_detail_list');   // E-59
    Route::get('/sale_detail_note', 'MyAccountController@saleDetailNote')->name('user.myaccount.sale_detail_note');   // E-60
    Route::get('/asking', 'MyAccountController@asking')->name('user.myaccount.asking');   // E-62
    Route::post('/add_asking', 'MyAccountController@ajaxAddAsking')->name('user.myaccount.add_asking');
    Route::post('/ques_class', 'MyAccountController@ajaxQuesClass')->name('user.myaccount.ques_class');
    Route::post('/question', 'MyAccountController@ajaxQuestion')->name('user.myaccount.question');
    Route::get('/quit', 'MyAccountController@quit')->name('user.myaccount.quit');   // E-64
    Route::post('/set_quit', 'MyAccountController@ajaxSetQuit')->name('user.myaccount.set_quit');

    //kh
    Route::get('/friend_invite', 'MyAccountController@friendInvite')->name('user.myaccount.friend_invite');    //alpha-14-17.php
    Route::get('/coupon_intro/{mode?}', 'MyAccountController@couponIntro')->name('user.myaccount.coupon_intro');       //alpha-18.php
    Route::get('/coupon_publish', 'MyAccountController@couponPublish')->name('user.myaccount.coupon_publish'); //alpha-19.php
    Route::get('/coupon_box/{mode?}', 'MyAccountController@couponBox')->name('user.myaccount.coupon_box');             //alpha-23_24.php
    Route::get('/confirm', 'MyAccountController@confirm')->name('user.myaccount.confirm');                              //alpha-31_32.php
    Route::get('/confirm_drive', 'MyAccountController@confirmDrive')->name('user.myaccount.confirm_drive');            //alpha-33-1.php
    Route::get('/confirm_health', 'MyAccountController@confirmHealth')->name('user.myaccount.confirm_health');         //alpha-33-2_3.php
    Route::get('/confirm_number', 'MyAccountController@confirmNumber')->name('user.myaccount.confirm_number');         //alpha-33-4.php
    Route::get('/confirm_student', 'MyAccountController@confirmStudent')->name('user.myaccount.confirm_student');      //alpha-33-5.php
    Route::get('/confirm_passport', 'MyAccountController@confirmPassport')->name('user.myaccount.confirm_passport');   //alpha-33-6.php
    Route::get('/confirm_jyumin', 'MyAccountController@confirmJyumin')->name('user.myaccount.confirm_jyumin');         //alpha-33-7_8.php
    Route::get('/confirm_resident', 'MyAccountController@confirmResident')->name('user.myaccount.confirm_resident');   //alpha-33-9_10.php
    Route::get('/confirm_permanent', 'MyAccountController@confirmPermanent')->name('user.myaccount.confirm_permanent');    //alpha-33-11.php
    Route::get('/confirm_again', 'MyAccountController@confirmAgain')->name('user.myaccount.confirm_again');                //alpha-36.php
    Route::get('/ques_cate', 'MyAccountController@quesCate')->name('user.myaccount.ques_cate');                            //beta-1.php
    Route::get('/ques_list/{id?}', 'MyAccountController@quesList')->name('user.myaccount.ques_list');                            //beta-2-2.php
    Route::get('/ques_cate_small/{id?}', 'MyAccountController@quesCateSmall')->name('user.myaccount.ques_cate_small');          //beta-2.php
    Route::get('/ques_detail/{id?}', 'MyAccountController@quesDetail')->name('user.myaccount.ques_detail');                      //beta-6.php

    Route::put('/confirm_drive_put', 'MyAccountController@putConfirmDrive')->name('user.myaccount.confirm_drive_put');
    Route::get('/ques_search', 'MyAccountController@quesSearch')->name('user.myaccount.ques_search');                      //beta-3.php
    Route::post('/coupon_publish_post', 'MyAccountController@postCouponPublish')->name('user.myaccount.post_coupon_publish');                      //beta-3.php
    Route::post('/confirm_user_info', 'MyAccountController@postUserInfo')->name('user.myaccount.postUserInfo');
    Route::post('/coupon_del', 'MyAccountController@couponDelete')->name('user.myaccount.coupon_del');


    //kkj
    Route::get('/reg_senpai', 'MyAccountController@regSenpai')->name('user.myaccount.reg_senpai');                      //C-2.php

    //post
    Route::post('/reg_senpai_post', 'MyAccountController@postRegSenpai')->name('user.myaccount.reg_senpai_post');
    Route::post("/senpai_favourite", 'MyAccountController@ajaxSenpaiFavourite')->name('user.myaccount.ajaxSenpaiFavourite');

    // export pdf
    Route::get('/receipt', 'MyAccountController@receipt')->name('user.myaccount.receipt');
});

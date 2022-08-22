@extends('user.layouts.app')

@section('title', '承認・辞退')

@section('content')

    @include('user.layouts.header_under')

    @php $sex = ['', '女性', '男性']; @endphp

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">
        <input type="hidden" id="lr_id" value="{{$req_info['lr_id']}}">
        <input type="hidden" id="kouhai_id" value="{{$req_info['user']['id']}}">
        <input type="hidden" id="until_confirm" value="{{\App\Service\CommonService::getMDAndWeek($req_info['lr_until_confirm'])}}">
        <input type="hidden" id="ls_title" value="{{$req_info['lesson']['lesson_title']}}">
        <input type="hidden" id="lesson_img" value="{{\App\Service\CommonService::unserializeData($req_info['lesson']['lesson_image'])[0]}}">
      <!--main_-->
        <div  id="form1">
              <section>
                  <div class="lesson_info_area">
                    <ul class="teacher_info_02">
                     <li class="icon"><img src="{{\App\Service\CommonService::getUserAvatarUrl($req_info['user']['user_avatar'])}}" class="プロフィールアイコン"></li>
                     <li class="about_teacher">
                        <div class="profile_name"><p>{{$req_info['user']['name']}}<span>（{{\App\Service\CommonService::getAge($req_info['user']['user_birthday'])}}）{{$sex[$req_info['user']['user_sex']]}}</span></p></div>
                        <div><p class="orange_link icon_arrow orange_right"><a href="{{route('user.myaccount.profile', ['user_id'=>$req_info['user']['id']])}}">プロフィール</a></p></div>
                     </li>
                    </ul>
                  </div>
              </section>
            <section class="pb10">
                   <div class="inner_box">
                    <h3 class="summary_ttl">
                     <span>レッスン概要</span>
                     <span class="shounin_kigen">承認期限：<big>{{ltrim(date('m', strtotime($req_info['lr_until_confirm'])), '0')}}</big>月<big>{{ltrim(date('d', strtotime($req_info['lr_until_confirm'])), '0')}}</big>日</span>
                    </h3>
                    <div class="white_box">
                     <div class="lesson_ttl_02">
                        <p>
                            {{$req_info['lesson']['lesson_title']}}
                        </p>
                     </div>
                    </div>
                    </div>

                <div class="inner_box">
                    <h3>レッスン場所</h3>
                    <div class="white_box">
                        <div class="lesson_place">
                            @if($req_info['lr_pos_discuss'] == 1)
                                <p>
                                    {{ $req_info['discuss_lesson_area'] }}
                                </p>
                                <p>
                                    {{ $req_info['lr_address'] }}
                                </p>
                            @else
                                <p>
                                    {{ implode('/', $req_info['lesson']['lesson_area_names']) }}
                                </p>
                            @endif
                        </div>

                        @if($req_info['lr_pos_discuss'] == 1)
                            <div class="balloon balloon_blue font-small">
                                <p>{{ $req_info['lr_address_detail'] }}</p>
                            </div>
                        @else
                            <div class="balloon balloon_blue font-small">
                                <p>{{ $req_info['lesson']['lesson_pos_detail'] }}</p>
                            </div>
                        @endif

                    </div>
                </div>

                   <div class="inner_box evaluation_area">
                    <h3>評価（{{\App\Service\CommonService::showFormatNum($req_info['evalution_count'])}}件）</h3>
                   <input id="evaluation-check1" name="acd" class="acd-check" type="checkbox">
                       @if($req_info['evalution_count'] < 3)
                           <label class="acd-label" for="evaluation-check1">{{ $req_info['user']['name'] }}さんの評価を確認する</label>
                           <div class="acd-content evaluation-content">
                               <div class="box-hide">
                                   <h4>{{ $req_info['user']['name'] }}さんの評価件数が3件未満のため表示できません。</h4>
                               </div>
                               <p class="acc-close cur_point fs-13">閉じる</p>
                           </div>
                       @else
                    <label class="acd-label" for="evaluation-check1">{{ $req_info['user']['name'] }}さんの評価を確認する</label>
                       @if(isset($req_info['evalution']) && !empty($req_info['evalution']))
                        <div class="acd-content evaluation-content">
                            <div class="box-hide">
                                 <h4>他のセンパイは{{ $req_info['user']['name'] }}さんをこのように評価しています</h4>
                                 <ul class="evaluation_list">
                                     @foreach($req_info['evalution'] as $key => $value)
                                          <li>
                                              <div>{{$value['type_name']}}</div>
                                              <div class="score"><span>{{$value['percent']}}</span></div>
                                          </li>
                                     @endforeach
                                 </ul>
                                </div>
                            <p class="acc-close cur_point fs-13">閉じる</p>
                        </div>
                       @endif
                   @endif

                                <!--評価が３件未満だった場合-->
                                <!--
                                <div class="acd-content evaluation-content">
                        <div class="box-hide">
                     <h4>ミクさんの評価件数が3件未満のため表示できません。</h4>
                            </div>
                        <p class="acc-close">閉じる</p>
                    </div>
            -->

                   </div>
                @if(isset($req_info['lesson_request_schedule']) && count($req_info['lesson_request_schedule']) > 0)
                   <div class="inner_box  for-warning">
                    <h3>承認するレッスン</h3>
                       <p class="warning"></p>
                     <div class="white_box">
                        <div class="check-box"  id="approval_checkbox">
                            @foreach($req_info['lesson_request_schedule'] as $k=>$v)
                                @php
                                    $price1 = \App\Service\CommonService::showFormatNum($v['lrs_amount']);
                                    $price2 = intval($v['lrs_amount']) + intval($v['lrs_traffic_fee']);
                                    $price2 =\App\Service\CommonService::showFormatNum($price2);
                                    $price3 = intval($v['lrs_amount']) + intval($v['lrs_traffic_fee']) - intval($v['lrs_fee']);
                                    $price3 = \App\Service\CommonService::showFormatNum($price3);
                                    $type_arr = array('A', 'B', 'C');
                                    $fee_type = $type_arr[$v['lrs_fee_type']];
                                    $scd = date('Y-n-j', strtotime($v['lrs_date'])).'|'.\App\Service\CommonService::getStartAndEndTime($v['lrs_start_time'], $v['lrs_end_time']).'|'.$price1.'|'.$price2.'|'.$price3.'|'.$fee_type;

                                    $not_enable = false;
                                    $start_date = \Carbon\Carbon::parse($v['lrs_date'].' '.$v['lrs_start_time'])->format('Y-m-d H:i:s');
                                    $now = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                                    if ($start_date < $now) {
                                        $not_enable = true;
                                    }
                                @endphp
                            <div class="clex-box_02">
                                <input class="{{ $not_enable ? : 'chk-approval' }}" type="checkbox" name="approval" value="{{$v['lrs_id']}}" id="approval-{{$v['lrs_id']}}" onclick="set_approval_checked({{$v['lrs_id']}})"  scd="{{$scd}}" {{ $not_enable ? 'disabled' : '' }}>
                                <label class="{{ $not_enable ? 'disable-label' : '' }}" for="approval-{{$v['lrs_id']}}"><p>{{\App\Service\CommonService::getMD($v['lrs_date'])}}（{{\App\Service\CommonService::getWeekday($v['lrs_date'])}}）{{\App\Service\CommonService::getStartAndEndTime($v['lrs_start_time'], $v['lrs_end_time'])}}　{{\App\Service\CommonService::showFormatNum($v['lrs_amount'])}}円</p></label>
                            </div>
                            @endforeach
                        </div>
                      </div>
                    </div>
                @endif

                    <div class="inner_box">
                    <h3>条件</h3>
                    <p class="base_txt">
                     出張交通費をお願いしますか？
                    </p>
                    </div>
                    <ul class="small_select">
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="popular">
                                    @php $traffic_max_amount = \App\Service\SettingService::getSetting('traffic_max_amount', 'int') @endphp
                                    @for($i=100; $i<$traffic_max_amount; $i=$i+100)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                    <option value="{{$traffic_max_amount}}">{{$traffic_max_amount}}</option>
                                </select>
                            </div>
                        </li>
                        <li>円以下</li>
                    </ul>
                        <p class="modal-link modal-link_blue">
                         <a class="modal-syncer button-link" data-target="modal-business-trip">出張交通費について</a>
                        </p>
                @if(isset($req_info['lesson_request_schedule']) && count($req_info['lesson_request_schedule']) > 0)
                    <div class="inner_box  for-warning">
                        <h3>辞退するレッスン</h3>
                        <p class="warning"></p>
                        <div class="white_box">
                            <div class="check-box" id="cancel_checkbox">
                                @foreach($req_info['lesson_request_schedule'] as $k=>$v)
                                    @php
                                        $scd = date('Y-n-j', strtotime($v['lrs_date'])).'|'.\App\Service\CommonService::getStartAndEndTime($v['lrs_start_time'], $v['lrs_end_time']);

                                        $not_enable = false;
                                        $start_date = \Carbon\Carbon::parse($v['lrs_date'].' '.$v['lrs_start_time'])->format('Y-m-d H:i:s');
                                        $now = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                                        if ($start_date < $now) {
                                            $not_enable = true;
                                        }
                                    @endphp
                                    <div class="clex-box_02">
                                        <input class="{{ $not_enable ? : 'chk-cancel' }}" type="checkbox" name="cancel" value="{{$v['lrs_id']}}" id="cancel-{{$v['lrs_id']}}" onclick="set_cancel_checked({{$v['lrs_id']}})"  scd="{{$scd}}">
                                        <label for="cancel-{{$v['lrs_id']}}"><p>{{\App\Service\CommonService::getMD($v['lrs_date'])}}（{{\App\Service\CommonService::getWeekday($v['lrs_date'])}}）{{\App\Service\CommonService::getStartAndEndTime($v['lrs_start_time'], $v['lrs_end_time'])}}　{{\App\Service\CommonService::showFormatNum($v['lrs_amount'])}}円</p></label>
                                    </div>
                                    @if(isset($cancel_reason_types) && count($cancel_reason_types) > 0)
                                        <div class="inner_box sub_box for-detail-warning"  id="cancel_box_{{ $v['lrs_id'] }}" style="display: none;">
                                            <h3 class="must">辞退の理由</h3>
                                            <div class="check-box"  id="reason_checkbox_{{ $v['lrs_id'] }}">
                                                @foreach( $cancel_reason_types as $key => $value)
                                                    <div class="clex-box_02">
                                                        <input type="checkbox" name="commitment_{{ $v['lrs_id'] }}" value="{{ $value['crt_id'] }}" id="reason-{{ $v['lrs_id'] }}-{{ $value['crt_id'] }}" @if ( $value['crt_id'] == config('const.senpai_cancel_other_reason_id') ) class= "click-balloon" onclick="showOtherReasonBalloon({{$v['lrs_id']}})" @endif>
                                                        <label for="reason-{{ $v['lrs_id'] }}-{{ $value['crt_id'] }}"><p>{{ $value['crt_content'] }}</p></label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="balloon_area" id="makeImg_{{ $v['lrs_id'] }}">
                                                <div class="balloon balloon_white">
                                                    <textarea placeholder="キャンセルの理由を100字以内でご記入ください。" cols="50" rows="10" maxlength="100" name="cancel_note_{{ $v['lrs_id'] }}" id="cancel_note_{{ $v['lrs_id'] }}"></textarea>
                                                </div>
                                            </div>
                                            <p class="warning"></p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                    <p class="form_txt gray_txt">
                        ※キャンセルの理由は先輩に通知されます
                    </p>
            </section>
            <div class="white-bk"  id="btn_area1">
                    <div class="button-area">
                        <div class="btn_base btn_orange shadow"><button onclick="go_confirm()">内容を確定する</button></div>
                  </div>
            </div>
        </div>

        <!-- 内容の確認 *********************************************************** -->
        <div id="form2" style="display: none">
            <section class="pb10">
                <div class="inner_box" id="approval_case">
                    <h3>承認するレッスン</h3>
                </div>
                <div class="kome_txt pt0">
                    <p class="mark_left mark_kome">
                        手取り金額については、<br>
                        レッスンのキャンセルや追加予約、コウハイがあなたの発行したクーポンを使用した場合に変動することがあります。
                    </p>
                </div>
                <div class="inner_box" id="cancel_case">
                    <h3>辞退するレッスン</h3>
                </div>
            </section>
        </div>
        <div class="white-bk" id="btn_area2" style="display: none;">
            <div class="button-area">
                <div class="btn_base btn_orange shadow">
                    <button  class="btn-send2" onclick="save();">この内容で送信</button>
                </div>
            </div>
        </div>
    </div><!-- /contents -->

    <div  id="allow_sample" style="display: none;">
        <div class="white_box">
            <ul class="list_box">
                <li class="due_date">
                    <div><p><span class="checked_date"></span></p></div>
                    <div class="jitai"></div>
                </li>
                <li>
                    <div>
                        <p>レッスン料</p>
                        <p class="price_mark tax-in tax_in"></p>
                    </div>
                    <div>
                        <p class="modal-link">
                            <a class="modal-syncer" data-target="modal-service">手数料率</a>
                        </p>
                        <p class="price_mark fee_type"></p>
                    </div>
                    <div>
                        <p>手取り金額</p>
                        <p class="price_mark tax-in fee_out"></p>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div  id="cancel_sample" style="display: none;">
        <div class="white_box">
            <ul class="list_box">

            </ul>
        </div>
    </div>

    <!-- モーダル部分 *********************************************************** -->
    <iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
    <div class="modal-wrap completion_wrap ok">
        <div id="modal-send" class="modal-content ok">
            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_success_ttl">
                        承認・辞退内容を<br>
                        送信しました
                    </h2>
                    <h2 class="modal_failed_ttl" style="display: none;">
                        承認・辞退内容送信が<br>
                        失敗しました
                    </h2>

                    <div class="modal_txt">
                        <p>
                            レッスンが購入されると<br>
                            トークルームが開きます。
                        </p>
                    </div>
                </div>
            </div>

            <div class="button-area">
                <div class="btn_base btn_ok">
                    <a href="#" onclick="go_where()">OK</a>
                </div>
            </div>
            <input type="hidden" id="save_result" value="0">
        </div>
    </div>
    <div id="modal-overlay2" style="display: none;"></div>

    @include ('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

@section('page_css')
    <style>
        .balloon_open {
            top: -10px !important;
        }
    </style>
@endsection

@section('page_js')
	<script src="{{ asset('assets/user/js/validate.js') }}"></script>
	<script>
        var nowModalSyncer = null;
        var modalClassSyncer = "modal-syncer";
        function save(){
            var lrs_id = 0;
            var lr_id = $('#lr_id').val();
            var kouhai_id = $('#kouhai_id').val();
            var until_confirm = $('#until_confirm').val();
            var ls_title = $('#ls_title').val();
            var lesson_img = $('#lesson_img').val();
            var cancel_notes = [];
            var approval_ids = [];
            var cancel_ids = [];
            var crt_ids = [];
            if($('input[name=approval]:checked').length > 0){
                $('input[name=approval]:checked').each(function(){
                    approval_ids.push($(this).val());
                });
            }
            if($('input[name=cancel]:checked').length > 0){
                $('input[name=cancel]:checked').each(function(){
                    cancel_ids.push($(this).val());
                });
                @if(isset($req_info['lesson_request_schedule']) && count($req_info['lesson_request_schedule']) > 0)
                    @foreach($req_info['lesson_request_schedule'] as $k=>$v)
                        var sub_crt_ids = [];
                        $("input[name=commitment_{{ $v['lrs_id'] }}]:checked").each(function(){
                            sub_crt_ids.push($(this).val());
                        });
                        crt_ids["{{ $v['lrs_id'] }}"] = sub_crt_ids;

                        cancel_notes["{{ $v['lrs_id'] }}"] = $("#cancel_note_{{ $v['lrs_id'] }}").val()
                    @endforeach
                @endif
            }
            $.ajax({
                type: "post",
                url: '{{ route('user.syutupinn.reserve_req_save') }}',
                data: { lr_id: lr_id, kouhai_id: kouhai_id, until_confirm: until_confirm, ls_title: ls_title, lesson_img: lesson_img, approval_ids: approval_ids, crt_ids: crt_ids, cancel_ids: cancel_ids, cancel_note: cancel_notes, _token:'{{csrf_token()}}'},
                dataType: 'json',
                success: function (result) {
                    if(result.state){
                        success_open_modal();
                        $('#save_result').val(1);
                    }else{
                        failed_open_modal();
                        $('#save_result').val(0);
                    }
                }
            });
        }

		function set_approval_checked(id) {
            onRemoveError();
            if($('#approval-'+id).prop('checked')){
                $('#cancel-'+id).prop('checked', !$('#approval-'+id).prop('checked'));
            }
        }
        function set_cancel_checked(id) {
            onRemoveError();
            if($('#cancel-'+id).prop('checked')){
                $('#approval-'+id).prop('checked', !$('#cancel-'+id).prop('checked'));
            }
            if($('input[name=cancel]:checked').length > 0){
                $('#cancel_box_'+id).show();
			}else{
                $('#cancel_box_'+id).hide();
                $('input[name=commitment]').prop('checked', false);
			}
        }
        function valid() {
            onRemoveError();
            var is_allow = true;
            var is_cancel = true;
            var is_reason = true;
            var is_balloon = false;
            var cnt_approval = $('input[name=approval]').length;
            var cnt_approval_sel = $('input[name=approval]:checked').length;
            var cnt_cancel_sel = $('input[name=cancel]:checked').length;
            if($('input[name=approval]:checked').length == 0) is_allow = false;
            if($('input[name=cancel]:checked').length == 0) is_cancel = false;
            /*if($('input[name=commitment]:checked').length == 0) is_reason = false;*/

            /*var cnt_approval = $('.chk-approval').length;
            var cnt_approval_sel = $('.chk-approval:checked').length;
            var cnt_cancel_sel = $('.chk-cancel:checked').length;
            if($('.chk-approval:checked').length == 0) is_allow = false;
            if($('.chk-cancel:checked').length == 0) is_cancel = false;
            if($('input[name=commitment]:checked').length == 0) is_reason = false;*/
            if(!is_allow && !is_cancel || (cnt_approval_sel + cnt_cancel_sel) < cnt_approval){
                /*addError($('#approval_checkbox'), '承認するレッスンを選択してください。');
                addError($('#cancel_checkbox'), '辞退するレッスンを選択してください。');*/
                addError($('#approval_checkbox'), '全ての日程について承認又は辞退を選択して下さい。');
                addError($('#cancel_checkbox'), '全ての日程について承認又は辞退を選択して下さい。');
                return false;
			}

            if( is_cancel) {
                @if(isset($req_info['lesson_request_schedule']) && count($req_info['lesson_request_schedule']) > 0)
                    @foreach($req_info['lesson_request_schedule'] as $k=>$v)

                        if($("#cancel-{{ $v['lrs_id'] }}").prop('checked') && $("input[name=commitment_{{ $v['lrs_id'] }}]:checked").length == 0) {
                            addDetailError($("#reason_checkbox_{{ $v['lrs_id'] }}"), '辞退の理由を選択してください。');
                            is_reason = false;
                        }
                    @endforeach
                @endif

                if(!is_reason) {
                    return false;
                }
            }

			if(is_cancel && is_reason){
                @if(isset($req_info['lesson_request_schedule']) && count($req_info['lesson_request_schedule']) > 0)
                    @foreach($req_info['lesson_request_schedule'] as $k=>$v)
                        $("input[name=commitment_{{ $v['lrs_id'] }}]:checked").each(function(){
                            if($(this).hasClass('click-balloon')) {
                                if($("#cancel_note_{{ $v['lrs_id'] }}").val().trim() == ''){
                                    addDetailError($("#reason_checkbox_{{ $v['lrs_id'] }}"), 'キャンセルの理由を正確に入力してください。');
                                    is_balloon = true;
                                }
                            }
                        });
                    @endforeach
                @endif
                if(is_balloon){
                    return false;
                }
            }
            return true;
        }
        function go_confirm() {
            if(!valid()) return;
            $('#form1').hide();
            $('#form2').show();
            $('#btn_area1').hide();
            $('#btn_area2').show();
            $('#header_under_ttl .header_area h1').html("内容の確認")
            $('#header_under_ttl .header_area .h-icon p').hide();
            $('#header_under_ttl .header_area .h-icon').append('<p id="temp_p">' +
                '<button type="button" onclick="close_confirm()">' +
                '<img src="{{ asset('assets/user/img/arrow_left2.svg') }}" alt="戻る">' +
                '</button>' +
                '</p>');

            if($('input[name=approval]:checked').length > 0){
                $('#approval_case').show();
                $('#approval_case').html("<h3>承認するレッスン</h3>");
                $('input[name=approval]:checked').each(function () {
                    var scd = $(this).attr('scd').split('|');
                    var d = scd[0].split('-');
                    var str_html = d[0] + '<small>年</small>            ' +
                        d[1] + '            <small>月</small>' +
                        d[2]  + '            <small>日</small>';
                    $('#allow_sample .checked_date').html(str_html);
                    str_html = '<p>' + scd[1] + '</p>' + '<p class="price_mark tax-in">' + scd[2] + '</p>';
                    $('#allow_sample .jitai').html(str_html);
                    $('#allow_sample .tax_in').html(scd[3]);
                    $('#allow_sample .fee_out').html(scd[4]);
                    $('#allow_sample .fee_type').html(scd[5]);

                    $('#approval_case').append($('#allow_sample').html());
                });
            }else{
                $('#approval_case').hide();
            }

            if($('input[name=cancel]:checked').length > 0){
                $('#cancel_case').show();
                $('#cancel_case').html("<h3>辞退するレッスン</h3>");
                $('#cancel_sample .white_box .list_box').html("")
                $('input[name=cancel]:checked').each(function () {
                    var scd = $(this).attr('scd').split('|');
                    var d = scd[0].split('-');
                    var str_html = '<li class="due_date">' +
                        '<div><p><span class="checked_date">' +
                        d[0] + '<small>年</small>            ' +
                        d[1] + '            <small>月</small>' +
                        d[2]  + '            <small>日</small>'+
                        '</span></p></div>' +
                        '<div class="jitai">' +
                        '<p>' + scd[1] + '</p>' +
                        '</div>' +
                        '</li>';
                    $('#cancel_sample .white_box .list_box').append(str_html);
                });
                var reason = '';
                $('input[name=commitment]:checked').each(function () {
                    if(reason != '')
                        reason += '<br>理由：' + $(this).next().children('p').html();
                    else
                        reason += '理由：' + $(this).next().children('p').html();
                });
                if(reason != ''){
                    reason = '<li>' + reason +'</li>';
                    $('#cancel_sample .white_box .list_box').append(reason);
                }
                $('#cancel_case').append($('#cancel_sample').html());
            }else{
                $('#cancel_case').hide();
            }
            show_modal();
        }

        function show_modal() {
            var modals = document.getElementsByClassName(modalClassSyncer);

            for (var i = 0, l = modals.length; l > i; i++) {

                modals[i].onclick = function () {

                    this.blur();

                    var target = this.getAttribute("data-target");

                    if (typeof (target) == "undefined" || !target || target == null) {
                        return false;
                    }

                    nowModalSyncer = document.getElementById(target);

                    if (nowModalSyncer == null) {
                        return false;
                    }

                    if ($("#modal-overlay")[0]) return false;

                    $("body").append('<div id="modal-overlay"></div>');
                    $("#modal-overlay").fadeIn("fast");

                    centeringModalSyncer();

                    $(nowModalSyncer).fadeIn("slow");

                    $("#modal-overlay,#modal-close,#modal-close.start-btn").unbind().click(function () {
                        $('.start-active').addClass('appear');
                        $("#" + target + ",#modal-overlay").fadeOut("fast", function () {

                            $('#modal-overlay').remove();

                        });

                        nowModalSyncer = null;

                    });

                }

            }
        }

        function centeringModalSyncer() {

            //モーダルウィンドウが開いてなければ終了
            if (nowModalSyncer == null) return false;

            //画面(ウィンドウ)の幅、高さを取得
            var w = $(window).width();
            var h = $(window).height();

            //コンテンツ(#modal-content)の幅、高さを取得
            // jQueryのバージョンによっては、引数[{margin:true}]を指定した時、不具合を起こします。
            //		var cw = $( nowModalSyncer ).outerWidth( {margin:true} ) ;
            //		var ch = $( nowModalSyncer ).outerHeight( {margin:true} ) ;
            var cw = $(nowModalSyncer).outerWidth();
            var ch = $(nowModalSyncer).outerHeight();

            //センタリングを実行する
            $(nowModalSyncer).css({
                "left": ((w - cw) / 2) + "px",
                "top": ((h - ch) / 2) + "px"
            });

        }

        function close_confirm() {
            $('#form1').show();
            $('#form2').hide();
            $('#btn_area1').show();
            $('#btn_area2').hide();
            $('#header_under_ttl .header_area h1').html("承認・辞退")
            $('#header_under_ttl .header_area .h-icon #temp_p').remove();
            $('#header_under_ttl .header_area .h-icon p').show();
            cancel_modal();
        }

        function cancel_modal(){
            $('.modal-wrap').fadeOut();
            $('#modal-send').fadeOut();
            $('#modal-overlay2').fadeOut();
        }

        function success_open_modal(){
            $('#modal-send .modal_success_ttl').show();
            $('#modal-send .modal_failed_ttl').hide();
            $('#modal-send .modal_txt').show();
            $('.modal-wrap').fadeIn();
            $('#modal-send').fadeIn();
            $('#modal-overlay2').fadeIn();
        }

        function failed_open_modal(){
            $('#modal-send .modal_success_ttl').hide();
            $('#modal-send .modal_failed_ttl').show();
            $('#modal-send .modal_txt').hide();

            $('.modal-wrap').fadeIn();
            $('#modal-send').fadeIn();
            $('#modal-overlay2').fadeIn();
        }

        function go_where() {
            if($('#save_result').val() == 1){
                location.href = '{{route('user.syutupinn.lesson_list')}}';
            }else{
                cancel_modal();
            }
        }

        function showOtherReasonBalloon(id) {
            var wObjballoon = document.getElementById("makeImg_"+id);
            if (wObjballoon.className == "balloon_area") {
                wObjballoon.className = "balloon_open";
            } else {
                wObjballoon.className = "balloon_area";
            }
        }

    </script>
@endsection


@extends('user.layouts.app')

@section('title', '承認・辞退')

@section('content')

	@php $sex = ['', '女性', '男性']; @endphp

    @include('user.layouts.header_under')

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

                <div class="inner_box">
                <h3>希望レッスン時間</h3>
                    <div class="white_box form_txt">
                        <p>{{\App\Service\CommonService::showFormatNum($req_info['lr_hope_mintime'])}}分～{{\App\Service\CommonService::showFormatNum($req_info['lr_hope_maxtime'])}}分</p>
                    </div>
                </div>

               <div class="inner_box  for-warning">
                <h3>希望日時<small class="normal">　対応可能な時間帯があれば選択してください</small></h3>
                 <p class="warning"></p>
                 <div class="white_box">
                    <div class="radio-list">
                        @if(isset($req_info['lesson_request_schedule']) && count($req_info['lesson_request_schedule']) > 0)
                            @foreach($req_info['lesson_request_schedule'] as $k=>$v)
                                @php
                                    $y =date('Y', strtotime($v['lrs_date']));
                                    $m = ltrim(date('m', strtotime($v['lrs_date'])), '0');
                                    $d = ltrim(date('d', strtotime($v['lrs_date'])), '0');
                                    $w = date('w', strtotime($v['lrs_date']));
                                    $w = config('const.week_days')[$w];
                                    // $start_time =  date('h:i', strtotime($v['lrs_start_time']));
                                    $start_time =  \Carbon\Carbon::parse($v['lrs_start_time'])->format('H:i');
                                    // $end_time =  date('h:i', strtotime($v['lrs_end_time']));
                                    $end_time =  \Carbon\Carbon::parse($v['lrs_end_time'])->format('H:i');
                                    $price1 = \App\Service\CommonService::showFormatNum($v['lrs_amount']);
                                    $price2 = intval($v['lrs_amount']) + intval($v['lrs_traffic_fee']);
                                    $price2 =\App\Service\CommonService::showFormatNum($price2);
                                    $price3 = intval($v['lrs_amount']) + intval($v['lrs_traffic_fee']) - intval($v['lrs_fee']);
                                    $price3 = \App\Service\CommonService::showFormatNum($price3);
                                    $type_arr = array('A', 'B', 'C');
                                    $fee_type = $type_arr[$v['lrs_fee_type']];
                                    $scd = $y.'-'.$m.'-'.$d.'-'.$start_time.'~'.$end_time.'-'.$price1.'-'.$price2.'-'.$price3.'-'.$fee_type;
                                @endphp
                        <div class="radio_mark_02" onclick="show_cancel(false);">
                            <input type="radio" name="commitment" value="{{$v['lrs_id']}}" id="r_{{$v['lrs_id']}}" scd="{{$scd}}">
                            <label for="r_{{$v['lrs_id']}}" class="pl0">
                                <p>
                                    {{$m}}月{{$d}}日（{{$w}}）{{$start_time}}~{{$end_time}}
                                </p>
                            </label>
                        </div>
                            @endforeach
                        @endif
                        <div class="radio_mark_02" onclick="show_cancel(true);">
                            <input type="radio" name="commitment" value="0" id="cancel_req">
                            <label for="cancel_req" class="pl0"><p>この出勤リクエストを辞退する　</p></label>
                        </div>
                    </div>
                  </div>
               </div>

              @if(isset($cancel_reason_types) && count($cancel_reason_types) > 0)
               <div class="inner_box for-warning" id="cancel_box" style="display: none;">
                    <h3 class="must">辞退の理由</h3>
                    <p class="warning"></p>
                     <div class="white_box">
                        <div class="check-box">
                                @foreach( $cancel_reason_types as $key => $value)
                                    <div class="clex-box_02" onclick="onRemoveError();">
                                        <input type="checkbox" name="commitment[]" value="{{$value['crt_id']}}" id="c{{$value['crt_id']}}" @if ( $value['crt_id'] == config('const.senpai_cancel_other_reason_id') ) class= "click-balloon" onclick="showBalloon()" @endif>
                                        <label for="c{{$value['crt_id']}}"><p>{{$value['crt_content']}}</p></label>
                                    </div>
                                @endforeach
                        </div>
                      </div>
               </div>
              @endif
              <div class="balloon_area" id="makeImg">
                  <div class="balloon balloon_white">
                      <textarea placeholder="キャンセルの理由を100字以内でご記入ください。" cols="50" rows="10" maxlength="100" name="cancel_note" id="cancel_note"></textarea>
                  </div>
                  <p class="form_txt gray_txt">
                      ※キャンセルの理由は先輩に通知されます
                  </p>
              </div>
        </section>
        @if(isset($req_info['lesson_request_schedule']) && count($req_info['lesson_request_schedule']) > 0)
            <div class="white-bk"  id="btn_area1">
                <div class="button-area">
                      <div class="btn_base btn_orange shadow"><button  onclick="go_confirm()">内容を確定する</button></div>
                </div>
            </div>
        @endif
    </div>

        <!-- 内容の確認 *********************************************************** -->
        <div id="form2" style="display: none">
            <section class="pb10">
                <div class="inner_box" id="allow_case">
                    <h3>辞退するレッスン</h3>
                    <div class="white_box">
                        <ul class="list_box">
                            <li class="due_date">
                                <div><p><span id="checked_date"></span></p></div>
                                <div class="jitai"  id="jitai"></div>
                            </li>
                            <li>
                                <div>
                                    <p>レッスン料</p>
                                    <p class="price_mark tax-in" id="tax_in"></p>
                                </div>
                                <div>
                                    <p class="modal-link">
                                        <a class="modal-syncer" data-target="modal-service">手数料率</a>
                                    </p>
                                    <p class="price_mark" id="fee_type"></p>
                                </div>
                                <div>
                                    <p>手取り金額</p>
                                    <p class="price_mark tax-in" id="fee_out"></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="kome_txt pt0">
                    <p class="mark_left mark_kome">
                    手取り金額については、<br>
                    レッスンのキャンセルや追加予約、コウハイがあなたの発行したクーポンを使用した場合に変動することがあります。
                    </p>
                </div>
                <div class="inner_box" id="cancel_case">
                    <h3>辞退するレッスン</h3>
                    <div class="white_box">
                        <ul class="list_box">
                            <li id="cancel_reason">

                            </li>
                        </ul>
                    </div>
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
    </div>
    <!-- /contents -->

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

        @include('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

@section('page_js')
    <script src="{{ asset('assets/user/js/validate.js') }}"></script>
    <script>
        function show_cancel(is_show) {
            onRemoveError();
            if(is_show)
                $('#cancel_box').show();
            else
                $('#cancel_box').hide();
        }
        function save(){
            var is_allow = 1;
            var lrs_id = 0;
            var lr_id = $('#lr_id').val();
            var kouhai_id = $('#kouhai_id').val();
            var until_confirm = $('#until_confirm').val();
            var ls_title = $('#ls_title').val();
            var lesson_img = $('#lesson_img').val();
            var cancel_note = $('#cancel_note').val();
            var cancel_reason = [];
            var radio_obj = $('.radio_mark_02 input[type="radio"]:checked');
            var reject_lrs_ids = [];
            $('.radio_mark_02 input[type="radio"]').each(function() {
                console.log($(this).prop('checked'));
                console.log($(this).val());
                if (!$(this).prop('checked') && $(this).val() != '0') {
                    reject_lrs_ids.push($(this).val());
                }
            });
            if($(radio_obj).attr('id') == 'cancel_req'){
                is_allow = 0;
                $('.clex-box_02 input[type="checkbox"]:checked').each(function(){
                    cancel_reason.push($(this).val());
                });
            }else{
                lrs_id = $(radio_obj).val();
            }
            $.ajax({
                type: "post",
                url: '{{ route('user.syutupinn.attend_req_save') }}',
                data: {lr_id: lr_id, kouhai_id: kouhai_id, until_confirm: until_confirm, ls_title: ls_title, lesson_img: lesson_img, is_allow: is_allow, lrs_id: lrs_id, reject_lrs_ids: reject_lrs_ids, crt_ids: cancel_reason, cancel_note: cancel_note,  _token:'{{csrf_token()}}'},
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
        function valid() {
            onRemoveError();
            var is_radio = false;
            $('.radio_mark_02 input[type="radio"]:checked').each(function () {
                is_radio = true;
            });
            var radio_obj = $('.radio_mark_02 input[type="radio"]:checked');
            if(is_radio){
                if($(radio_obj).attr('id') == 'cancel_req'){
                    var is_checked = false;
                    var is_balloon = false;
                    $('.clex-box_02 input[type="checkbox"]:checked').each(function(){
                        is_checked = true;
                        if($(this).hasClass('click-balloon'))
                            is_balloon = true;
                    });
                    if(!is_checked){
                        addError($('.check-box'), '辞退の理由を選択します。');
                    }else if(is_checked && is_balloon){
                        if($('#cancel_note').val().trim() == ''){
                            is_checked = false;
                            addError($('.check-box'), 'キャンセルの理由を正確に入力してください。');
                        }
                    }
                    return is_checked;
                }else{
                    return true;
                }
            }else{
                addError($('.radio-list'), '希望日時を選択します');
                return false;
            }
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

            var obj = $('.radio_mark_02 input[type="radio"]:checked');
            if($(obj).attr('id') == 'cancel_req'){
                $('#allow_case').hide();
                $('#cancel_case').show();
                var reason = '';
                $('.clex-box_02 input[type="checkbox"]:checked').each(function(){
                    if(reason != '')
                       reason += '<br>理由：' + $(this).next().children('p').html();
                    else
                        reason += '理由：' + $(this).next().children('p').html();
                });
                $('#cancel_reason').html(reason);
            }else{
                $('#allow_case').show();
                $('#cancel_case').hide();
                var scd = $(obj).attr('scd').split('-');
                var str_html = scd[0] + '<small>年</small>            ' +
                    scd[1] + '            <small>月</small>' +
                    scd[2]  + '            <small>日</small>';
                $('#checked_date').html(str_html);
                str_html = '<p>' + scd[3] + '</p>' + '<p class="price_mark tax-in">' + scd[4] + '</p>';
                $('#jitai').html(str_html);
                $('#tax_in').html(scd[5]);
                $('#fee_out').html(scd[6]);
                $('#fee_type').html(scd[7]);
            }
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

    </script>
@endsection


@extends('user.layouts.app')
@section('title', 'コウハイ評価')
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')

    <div id="contents" class="short">
        {{ Form::open(["method"=>"post", "name"=>"kouhaiEval", "id"=>"kouhaiEval"]) }}
            <input type="text" value="{{ $req_id }}" id="req_id" hidden>
            <input type="text" value="{{ $user_id }}" id="user_id" hidden>
            <input type="text" value="{{ $sch_id }}" id="sch_id" hidden>
            <section id="hyouka" class="pb0">
                <div class="inner_box ">
                    <p class="form_txt"> コウハイは自分の評価を確認することができないため、この評価はお相手には公開されません。<br>
                        ご入力いただいた評価は匿名でほかのユーザーと平均化され、この後輩の投稿に応募する先輩とリクエストを受け取ったセンパイのみ閲覧できます。 </p>
                </div>
                <div class="inner_box pb0">
                    @php $i = 1; @endphp
                    @foreach($eval_types as $k => $v)
                        @php
                            $eval_info = \App\Service\EvalutionService::getEvaluationValueByType($user_id, $sch_id, $v['et_id']);
                        @endphp
                        <h3 class="mark_left mark_square">{{$v['et_question']}}</h3>
                        <div class="check-flex">
                            <input type="checkbox" name="kouhai_eval" id="{{$v['et_id']}}"  value="" hidden>
                            <div class="clex-box_01"  type="yes">
                                @php
                                    if ($eval_info != "no_exist" && $eval_info == 1) {
                                        echo '<input type="radio" name="commitment'.$i.'" value="0" id="kodawari-c'.$i.'a" checked>';
                                    } else {
                                        echo '<input type="radio" name="commitment'.$i.'" value="0" id="kodawari-c'.$i.'a">';
                                    }
                                    echo '<label for="kodawari-c'.$i.'a">';
                                @endphp
                                <p>はい</p>
                                </label>
                            </div>
                            <div class="clex-box_01" type="no">
                                @php
                                    if ($eval_info != "no_exist" && $eval_info == 0) {
                                        echo '<input type="radio" name="commitment'.$i.'" value="0" id="kodawari-c'.$i.'b" checked>';
                                    } else {
                                        echo '<input type="radio" name="commitment'.$i.'" value="0" id="kodawari-c'.$i.'b">';
                                    }
                                    echo '<label for="kodawari-c'.$i.'b">';
                                @endphp
                                <p>いいえ</p>
                                </label>
                            </div>
                        </div>
                        <p class="error_text hide" id="warning_{{ $v['et_id'] }}">評価を進めてください。</p>
                        @php $i++; @endphp
                    @endforeach
                </div>
            </section>
            <div class="white-bk {{ $exist_eval ? 'hide' : '' }}">
                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <button type="button"  class="ajax-modal-syncer evalSendBtn" data-target="#modal-hyouka">
                            評価を送信
                        </button> </div>
                </div>
            </div>
        {{ Form::close() }}
    </div>
    <!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->
    <iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
    <div class="modal-wrap ok">
        <div id="modal-hyouka" class="modal-content ok">
            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl"> 評価を送信しました </h2>
                </div>
            </div>
            <div class="button-area">
                <div class="btn_base btn_ok">
                    <a href="{{route('user.talkroom.talkData', ['menu_type'=>config('const.menu_type.senpai'), 'room_id'=>$room_id])}}">OK</a>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-overlay2" style="display: none;"></div>
    <!-- ********************************************************* -->
@endsection

@section('page_css')
    <style>
        .error_text {
            padding: 0;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .hide {
            display: none !important;
        }
    </style>
@endsection

@section('page_js')
    <script>

        $(".evalSendBtn").click(function () {

                $('.error_text').each(function(e) {
                   if(!$(this).hasClass('hide')) {
                       $(this).addClass('hide');
                   }
                });

                var kouhaiEvals = $("input[name=kouhai_eval]");
                var types = {};
                let all_marked = true;
                for (var i = 0, l = kouhaiEvals.length; l > i; i++) {
                    types[kouhaiEvals[i].id] = kouhaiEvals[i].value;
                    if (kouhaiEvals[i].value == "" || kouhaiEvals[i].value == null) {
                        $('#warning_'+kouhaiEvals[i].id).removeClass('hide');
                        all_marked = false;
                    }
                }

                if(!all_marked) {
                    return;
                }

                $.ajax({
                    type: "post",
                    url: "{{ route('user.talkroom.evalPost') }}",
                    data:{
                        user_id: $("#user_id").val(),
                        req_id: $("#req_id").val(),
                        sch_id: $("#sch_id").val(),
                        kind: "{{ \App\Service\EvalutionService::SENPAIS_EVAL  }}",
                        types: types,
                        _token: "{{ csrf_token() }}",
                    },
                    dataType: "json",
                    success: function(result) {
                        if (!result.state)
                            $('.modal_ttl').html('評価送信が失敗しました。');

                        showAjaxModal($(".evalSendBtn"));
                    },
                    error: function () {
                        $('.modal_ttl').html('評価送信が失敗しました。');
                        showAjaxModal($(".evalSendBtn"));
                    }

                });

            }
        )

        $('div[type="yes"]').change(function(){
            var input = this.parentElement.firstElementChild;
            input.value = 1;
        });
        $('div[type="no"]').change(function(){
            var input = this.parentElement.firstElementChild;
            input.value = 0;
        });
    </script>
@endsection

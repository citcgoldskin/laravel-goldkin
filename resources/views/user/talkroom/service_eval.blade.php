@extends('user.layouts.app')
@section('title', 'サービス評価')
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')

    <div id="contents" class="short">

    {{ Form::open(["method"=>"post", "name"=>"service_eval", "id"=>"service_eval"]) }}
        <input type="text" value="{{ $request_id }}" id="request_id" hidden>
        <input type="text" value="{{ $user_id }}" id="user_id" hidden>
        <input type="text" value="{{ $schedule_id }}" id="schedule_id" hidden>
        <section id="hyouka" class="pb0">
            <div class="inner_box ">
                <p class="form_txt"> ご入力いただいた評価は匿名で他のユーザーと平均化されて表示されます。<br>また、ほかのユーザーにセンパイをオススメする際の基準に利用させて頂きます。</p>
            </div>
            <div class="inner_box pb0">
                @php $i = 1; @endphp
                @foreach( $eval_types as $k => $v)
                    <h3 class="mark_left mark_square">{{$v['et_question']}}</h3>
                    <div class="check-flex">
                        <input type="checkbox" id="type_{{$v['et_id']}}" value="0" hidden>
                        <div class="clex-box_01" type="yes">
                            @php
                                echo '<input type="radio" name="commitment'.$i.'" value="0" id="kodawari-c'.$i.'a">';
                                echo '<label for="kodawari-c'.$i.'a">';
                            @endphp
                                <p>はい</p>
                            </label>
                        </div>
                        <div class="clex-box_01" type="no">
                            @php
                                echo '<input type="radio" name="commitment'.$i.'" value="0" id="kodawari-c'.$i.'b">';
                                echo '<label for="kodawari-c'.$i.'b">';
                            @endphp
                                <p>いいえ</p>
                            </label>
                        </div>
                    </div>
                    @php $i++; @endphp
                @endforeach
            </div>
        </section>
        <div class="white-bk">
            <div class="button-area">
                <div class="btn_base btn_orange shadow">
                    <button type="button" class="evalSendBtn" >
                        評価を送信
                    </button></div>
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
                <div class="btn_base btn_ok"> <a href="D-6_8.php">OK</a> </div>
            </div>
        </div>
    </div>
    <div id="modal-overlay2" style="display: none;"></div>
    <!-- ********************************************************* -->
@endsection

@section('page_js')
    <script>

        $(".evalSendBtn").click(function () {
                var types = new Object();
                @foreach( $eval_types as $k => $v)
                    types.id_{{$v['et_id']}} = $("#type_{{$v['et_id']}}").val();
                @endforeach

                $.ajax({
                    type: "post",
                    url: "{{ route('user.talkroom.eval_post') }}",
                    data:{
                        user_id: $("#user_id").val(),
                        request_id: $("#request_id").val(),
                        schedule_id: $("#schedule_id").val(),
                        kind: "{{ \App\Service\EvalutionService::KOUHAIS_EVAL  }}",
                        types: types,
                        _token: "{{ csrf_token() }}",
                    },
                    dataType: "json",
                    success: function(result) {
                        if (!result.state)
                            $('.modal_ttl').html('評価送信が失敗しました。');

                        $('.modal-content').fadeIn();
                    },
                    error: function () {
                        $('.modal_ttl').html('評価送信が失敗しました。');
                        $('.modal-content').fadeIn();
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

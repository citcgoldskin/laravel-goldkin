@extends('user.layouts.app')
@section('title', 'キャンセル内容の確認')

<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')

    <div id="contents">

        {{ Form::open(["route" => "user.talkroom.cancelSchedules", "method" => "post", "name" => "form1", "id" => "form1"]) }}

            <input type="hidden" name="req_id" value="{{ $req_id }}">
            <section class="pb0">
                <div class="inner_box">
                    <ul class="list_area">
                        @foreach( $cancel_list as $key => $value )
                        <input type="hidden" name="cancel_list[]" value="{{ $value['lrs_id'] }}">
                        <li>
                            <strong>{{ date('Y', strtotime($value['lrs_date'])) }}</strong>年
                            <strong>{{ date('n', strtotime($value['lrs_date'])) }}</strong>月
                            <strong>{{ date('j', strtotime($value['lrs_date'])) }}</strong>日
                            （{{ \App\Service\CommonService::$week_arr[date('w', strtotime($value['lrs_date']))] }}）
                            　<strong>{{ \App\Service\CommonService::getStartAndEndTime($value['lrs_start_time'], $value['lrs_end_time']) }}</strong>
                        </li>
                        @endforeach
                    </ul>
                </div>

            </section>

            <section>

            <div class="inner_box">
                <div class="modal_inner">
                    <h4 id="circle-orange_ttl" class="shadow-orange_02">!</h4>
                    <h2 class="modal_ttl_03">
                        キャンセルしてよろしいですか？
                    </h2>

                </div>

            </div>


            <div class="button-area pt30">
                <div class="btn_base btn_orange shadow">
                    <a class="ajax_submit">キャンセル確定</a>
                </div>
                <div class="btn_base btn_pale-gray shadow-glay">
                    <a href="{{ route('user.talkroom.requestConfirm', ['request_id' => $req_id]) }}">キャンセルしない</a>
                </div>
            </div>


        </section>
        {{ Form::close() }}

    </div><!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->
    <input type="hidden" class="modal-syncer" data-target="modal-cancel_confirm" id="modal_confirm">
    <div class="modal-wrap completion_wrap">

        <div id="modal-cancel_confirm" class="modal-content ok">
            <div class="modal_body completion ok">
                <div class="modal_inner">

                    <h2 class="modal_ttl">
                        リクエストを<br>
                        キャンセルしました
                    </h2>

                    <div class="modal_txt">
                        <p>
                            またのご利用を<br>
                            お待ちしております。
                        </p>
                    </div>
                </div>
            </div>


            <div class="button-area type_under">
                <div class="btn_base btn_ok"><a href="{{ route('user.talkroom.list') }}">OK</a></div>
            </div>

        </div><!-- /modal-content -->



    </div>
    <div id="modal-overlay2" style="display: none;"></div>
    <!-- モーダル部分 / ここまで ************************************************* -->
    @include('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>
@endsection

@section('page_js')
 <script>
     $('.ajax_submit').click(function () {
         var postData = new FormData($("#form1").get(0));
         postData.append("_token", "{{csrf_token()}}");

         $.ajax({
             type: "post",
             url: '{{ route('user.talkroom.cancelSchedules') }}',
             data: postData,
             dataType: 'json',
             contentType: false,
             processData: false,
             success: function (result) {
                 if ( result.result_code == 'success' ) {
                     $('#modal_confirm').click();
                 } else {
                     location.href = "{{route('user.talkroom.list')}}"
                 }
             },
         });
     });
 </script>
@endsection

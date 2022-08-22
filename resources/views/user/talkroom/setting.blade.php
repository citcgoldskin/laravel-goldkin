@extends('user.layouts.app')

@section('content')
    <!--header_str-->
    <header id="header_under_ttl" class="talkroom_header_menu">
        <div class="header_area">
            <h1 class="talkroom_ttl">
                <div class="icon_s30">
                    {{--<a href="{{ route('user.myaccount.profile', Auth::user()->id) }}">--}}
                    @if(isset($obj_from_user))
                        <img src=" {{ \App\Service\CommonService::getUserAvatarUrl($obj_from_user['user_avatar']) }} " alt="アイコン">
                    @endif
                    {{--</a>--}}
                </div>
                <div>
                    @php
                        if (isset($obj_from_user)) {
                          echo $obj_from_user['name'];
                        }
                    @endphp
                    @if ($type == config('const.menu_type.kouhai'))
                        <small>センパイ</small>
                    @else
                        <small>コウハイ</small>
                    @endif
                </div>
            </h1>
            <div class="h-icon">
                <p><button type="button" onclick="history.back()"><img src=" {{ asset('assets/user/img/arrow_left2.svg') }}" alt="戻る"></button></p>
            </div>
        </div>
    </header>
    <!--header_end-->


    <!-- ************************************************************************
    本文
    ************************************************************************* -->
    <div id="contents" class="talkroom_contents">

        <div class="menu_btn_area mark">
            <ul>
                <li class="menu_notice">
                    <a class="modal click_btn {{ $isInformUser?"":"active" }}" id="notice_btn" data-target="#msg_notice"></a>
                </li>
                <li class="menu_block">
                    <a class="ajax-modal-syncer click_btn {{ $isBlockUser?"active":"" }} {{ $login_user_id == $fromUserId ? 'action-disable' : '' }}" id="block_btn"></a>
                </li>
                <li class="menu_report">
                    <a href="{{route('user.talkroom.appeal', ['type'=>$type , 'from_user_id'=>$fromUserId])}}" class="click_btn {{ $login_user_id == $fromUserId ? 'action-disable' : '' }}"></a>
                </li>
            </ul>
        </div>

        <!--main_-->
        <section>

            <div class="inner_box">
                <h3>レッスン履歴</h3>
                <div class="lesson_rireki">
                    @foreach( $schList as $k => $v)
                    <div class="board_box">
                        <p>{{\App\Service\CommonService::getYMD($v["lrs_date"])}} {{\App\Service\CommonService::getStartAndEndTime($v["lrs_start_time"],$v["lrs_end_time"])}}</p>
                        <p>{{$v["lesson_request"]["lesson"]["lesson_title"]}}</p>
                        <p>{{\App\Service\CommonService::showFormatNum($v["lrs_amount"])}}<small>円</small></p>
                    </div>
                    @endforeach
                    {{ $schList->links('vendor.pagination.senpai-pagination') }}
                </div>
            </div>

        </section>


    </div><!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->

    <div class="modalBox" id="msg_notice">
        <div class="modalInner">
        </div>
    </div>

    <!--   ******************************************************************* -->

    <div class="modal-wrap">
        <div id="modal-no_block" class="modal-content">

            <div class="modal_body">

                <div class="modal_inner">
                    <h4 id="circle-orange_ttl">！</h4>

                    <h2 class="modal_ttl">
                        取引中の相手は<br>
                        ブロックできません
                    </h2>

                    <div class="modal_txt">
                        <p>
                            このセンパイをブロックするには<br>
                            取引中の全てのレッスンを<br>
                            キャンセルしてください。
                        </p>
                    </div>

                    <div class="button-area">
                        <div class="btn_base btn_ok">
                            <a id="modal-close" class="button-link">OK</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--   ******************************************************************* -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

@section('page_js')
<script>
    $('#notice_btn').on('click',function(){
        var informState = false;
        if ($(this).hasClass('active'))
            informState = true;
        $.ajax({
            type: "post",
            url: " {{ route('user.talkroom.setInformState') }}",
            data: {
                informUserId: "{{ $fromUserId }}",
                _token: "{{ csrf_token() }}",
                state: informState
            },
            dataType: "json",
            success: function(data) {
                if (data.state) {
                    if (informState)
                        $('.modalInner').html('<p>通知をオンにしました</p>');
                    else
                        $('.modalInner').html('<p>通知をオフにしました</p>');
                    showTimeLimtModal($('#notice_btn'));
                    $('#notice_btn').toggleClass('active');
                 }else{

                }
            },
            error: function(){
                $('.modalInner').html('<p></p>'); //@todo insert msg
                showTimeLimtModal($('#notice_btn'));
            },
            complete: function() {
            }
        });
    });

    $('#block_btn').on('click',function(){
        var blockState = true;
        if ($(this).hasClass('active'))
            blockState = false;
            $.ajax({
                type: "post",
                url: " {{ route('user.talkroom.setBlockState') }}",
                data: {
                    blockUserId: "{{ $fromUserId }}",
                    _token: "{{ csrf_token() }}",
                    state: blockState
                },
                dataType: "json",
                success: function(data) {
                    var blockBtn = $('#block_btn');
                    if (data.isTransferUser) {
                        blockBtn.addClass('ajax-modal-syncer');
                        blockBtn.attr("data-target", "#modal-no_block");
                        showAjaxModal(blockBtn);
                    }else{
                        if (data.state) {
                            //modal-no_block
                            //modal-syncer
                            blockBtn.attr("data-target", "#msg_notice");

                            if (blockState)
                                $('.modalInner').html('<p>ブロックしました</p>');
                            else
                                $('.modalInner').html('<p>ブロック解除しました</p>');
                            showTimeLimtModal(blockBtn);
                            blockBtn.toggleClass('active');
                        }
                    }


                },
                error: function(){
                },
                complete: function() {
                }
            });

    });
</script>
@endsection

@extends('user.layouts.app')
@section('title', '退会する')
@section('$page_id', 'mypage')
@include('user.layouts.header_under')
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
<div id="contents">

    <!--main_-->
    <form action="./" method="post" name="form1" id="form1" target="senddata">

        <section class="taikai">
            <div class="inner_box">
                <h3>センパイを退会するにあたっての注意点</h3>

                <div class="white_box">
                    <ul class="coution_area">
                        <li>
                            <div class="base_txt">
                                <p class="mark_left2 mark_square">
                                    退会をすると全てのサービスがご利用できなくなりますのでご注意ください。
                                </p>
                            </div>
                        </li>

                        <li>
                            <div class="base_txt">
                                <p class="mark_left2 mark_square">
                                    注意事項が入ります。注意事項が入ります。注意事項が入ります。注意事項が入ります。注意事項が入ります。注意事項が入ります。注意事項が入ります。注意事項が入ります。注意事項が入ります。注意事項が入ります。
                                </p>
                            </div>
                        </li>

                        <li>
                            <div class="base_txt">
                                <p class="mark_left2 mark_square">
                                    レッスン時刻から60分経過後どちらもキャンセル申請を行わなかった場合、キャンセル料は発生しません。
                                </p>
                            </div>
                        </li>

                    </ul>

                </div>
            </div>

            <div class="inner_box">
                <h3>センパイを退会する条件</h3>

                <div class="white_box">

                    <div class="jouken_list">
                        <h4 class="user_ttl ttl_senpai">センパイ</h4>
                        <ul class="coution_area">
                            <li>
                                <div class="base_txt">
                                    <p class="mark_left2 mark_square">
                                        条件が入ります条件が入ります条件が入ります条件が入ります条件が入ります条件が入ります
                                    </p>
                                </div>
                            </li>

                            <li>
                                <div class="base_txt">
                                    <p class="mark_left2 mark_square">
                                        条件が入ります条件が入ります条件が入ります条件が入ります条件が入ります条件が入ります
                                    </p>
                                </div>
                            </li>

                            <li>
                                <div class="base_txt">
                                    <p class="mark_left2 mark_square">
                                        条件が入ります条件が入ります条件が入ります条件が入ります条件が入ります条件が入ります
                                    </p>
                                </div>
                            </li>

                        </ul>
                    </div>

                    <div class="jouken_list">
                        <h4 class="user_ttl ttl_kouhai">コウハイ</h4>
                        <ul class="coution_area">
                            <li>
                                <div class="base_txt">
                                    <p class="mark_left2 mark_square">
                                        条件が入ります条件が入ります条件が入ります条件が入ります条件が入ります条件が入ります
                                    </p>
                                </div>
                            </li>

                            <li>
                                <div class="base_txt">
                                    <p class="mark_left2 mark_square">
                                        条件が入ります条件が入ります条件が入ります条件が入ります条件が入ります条件が入ります
                                    </p>
                                </div>
                            </li>

                            <li>
                                <div class="base_txt">
                                    <p class="mark_left2 mark_square">
                                        条件が入ります条件が入ります条件が入ります条件が入ります条件が入ります条件が入ります
                                    </p>
                                </div>
                            </li>

                        </ul>
                    </div>

                </div>
            </div>

            <div class="button-area pt30">
                <div class="btn_base btn_orange shadow">
                    <a class="ajax-modal-syncer" data-target="#modal-taikai" id="quit_btn">
                        退会する
                    </a>
                </div>
            </div>

        </section>


    </form>

</div><!-- /contents -->

<!-- ********************************************************* -->
　
<div class="modal-wrap completion_wrap">
    <div id="modal-taikai" class="modal-content">

        <div class="modal_body completion">
            <div class="modal_inner">
                <h2 class="modal_ttl">
                    退会しました
                </h2>
            </div>
        </div>

        <div class="button-area">
            <div class="btn_base btn_ok">
                <a id="modal-close" class="button-link">OK</a>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- モーダル部分 / ここまで ************************************************* -->
@section('page_js')
    <script src="{{ asset('assets/user/js/validate.js') }}"></script>
    <script>
        $('#quit_btn').on('click', function () {
            $.ajax({
                type: "post",
                url: " {{ route('user.myaccount.set_quit') }}",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function (data) {
                    if (data.result) {
                        var quitBtn = $('#quit_btn');
                        var url = '{{route('home')}}';
                        showAjaxModal(quitBtn, url);
                    }
                }
            });
        });


    </script>
@endsection

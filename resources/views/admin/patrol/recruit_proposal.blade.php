@extends('admin.layouts.app')

@section('title', '提案内容の詳細')

@section('content')

    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.patrol.recruit.detail', ['recruit'=>$recruit->rc_id])])

        {{ Form::open(["route"=>["keijibann.recruit_book_comp"], "method"=>"get", "name"=>"form1", "id"=>"form1"]) }}

            <section>
                <div class="lesson_info_area">

                    <ul class="teacher_info_02">
                        <li class="icon"><img src="{{ \App\Service\CommonService::getUserAvatarUrl($proposal->proposalUser->user_avatar)}}" class="プロフィールアイコン"></li>
                        <li class="about_teacher">
                            <div class="profile_name"><p>{{$proposal->proposalUser['name']}}<span>（{{\App\Service\CommonService::getAge($proposal->proposalUser['user_birthday'])}}）{{\App\Service\CommonService::getSexStr($proposal->proposalUser['user_sex'])}}</span></p></div>
                            <div><p class="orange_link icon_arrow orange_right"><a href="{{ route('admin.staff.detail', ['staff'=>$proposal->proposalUser->id]) }}">プロフィール</a></p></div>
                        </li>
                    </ul>
                </div>
            </section>

            <section>
                <div class="inner_box">
                    <h3>レッスン場所</h3>
                    <div class="white_box">
                        <div class="lesson_place">
                            <p>
                                {{ implode('/', $recruit->recruit_area_names) }}
                            </p>
                        </div>
                        {{--<div class="balloon balloon_blue font-small">
                            <p>入り口の前で待ち合わせよろしくお願いします。</p>
                        </div>--}}
                    </div>
                </div>

                <div class="inner_box">
                    <h3>お支払い金額</h3>
                    <div class="white_box">
                        <ul class="list_box">
                            <li class="due_date">
                                <div>
                                    <p class="t-left">
                    <span>
                {{date('Y', strtotime($recruit['rc_date']))}}<small>年</small>
                    {{date('n', strtotime($recruit['rc_date']))}}<small>月</small>
                    {{date('d', strtotime($recruit['rc_date']))}}<small>日</small></span>
                                        <span>{{\App\Service\CommonService::getStartAndEndTime($recruit['rc_start_time'], $recruit['rc_end_time'])}}</span>
                                    <p class="price_mark"><em>{{\App\Service\CommonService::showFormatNum($proposal['pro_money'] * 11 / 10 + $proposal['pro_traffic_fee'])}}</em></p>
                                </div>
                            </li>

                            <li>
                                <div>
                                    <p>レッスン料</p>
                                    <p class="price_mark"><em>{{\App\Service\CommonService::showFormatNum($proposal['pro_money'])}}</em></p>
                                </div>
                                <div>

                                    <p class="modal-link">
                                        <a class="modal-syncer" data-target="modal-service">サービス料</a>
                                    </p>

                                    <p class="price_mark"><em>{{\App\Service\CommonService::showFormatNum($proposal['pro_money'] / 10)}}</em></p>

                                </div>

                                <div>
                                    <p>出張交通費</p>
                                    <p class="price_mark"><em>{{\App\Service\CommonService::showFormatNum($proposal['pro_traffic_fee'])}}</em></p>
                                </div>
                            </li>

                            <li>
                                <div>
                                    <p>合計（税込）</p>
                                    <p class="price_mark"><em>{{\App\Service\CommonService::showFormatNum($proposal['pro_money'] * 11 / 10 + $proposal['pro_traffic_fee'])}}</em></p>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>キャンセルポリシー</h3>
                    <ul class="list_box cancel_policy">

                        <li>
                            <div>
                                <p>ご利用当日のキャンセル</p>
                                <p class="space">
                                    <em>50%</em><br>
                                    <span>(1,375円)</span>
                                </p>
                            </div>
                        </li>
                        <li>
                            <div>
                                <p>ご利用前日のキャンセル</p>
                                <p class="space">
                                    <em>100</em>%<br>
                                    <span>(3,750円)</span>
                                </p>
                            </div>
                        </li>
                        <li>
                            <p class="list_txt">
                                ※ただし、ご利用の３時間前までにキャンセル申請されたレッスンについては、センパイが無料キャンセルに承認した場合のみキャンセル料が無料になります。<br>
                                <br>
                                予約日時を変更する場合や急な天候不順・体調不良が発生した場合は無理をせず、センパイに事情を説明して無料キャンセルのお願いをしてください。
                            </p>
                        </li>

                        <li>
                            <div>
                                <p class="modal-link">
                                    <a class="modal-syncer" data-target="modal-cancel_policy">キャンセルポリシーとは</a>
                                </p>
                            </div>


                        </li>

                    </ul>
                </div>
            </section>
        {{ Form::close() }}

    </div><!-- /contents -->

    @include('admin.layouts.modal')


@endsection

@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <style>
    </style>
@endsection

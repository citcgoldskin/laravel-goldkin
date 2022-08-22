@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.fraud_cancel_reserve.lesson_delete", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

            <input type="hidden" name="lesson_id" value="{{ $data['lesson']['lesson_id'] }}">
            <div class="swiper-container">
                <div class="swiper-inner">
                    <div class="profile">
                        <ol class="swiper-wrapper pt0 pb0">
                            <!-- Slides -->
                            @foreach($data['lesson_images'] as $k => $v)
                                @if($v)
                                    <li class="swiper-slide">
                                        <div class="swip_contents_block">
                                            <div class="slider_box">
                                                <div class="img-box">
                                                    <img src="{{\App\Service\CommonService::getLessonImgUrl($v)}}" alt="プロフィールイメージ画像">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination"></div>

                        <!-- If we need navigation buttons -->
                    </div>
                </div>
            </div>

            <section class="pt20">
                <div class="lesson_info_area">
                    <h3 class="senpai-message">{{$data['lesson']['lesson_title']}}</h3>

                    <ul class="title_info">
                        <li><span class="lesson_category">
                                 {{$data['lesson']['lesson_class']['class_name']}}
                             </span></li>
                        <li class="jisseki">
                            <p>レッスン実績 <span>{{\App\Service\CommonService::showFormatNum($data['schedule_count'])}}</span><span>件</span></p>
                        </li>
                        <li class="hyouka">このレッスンの評価を受けた後輩の評価<a href="#evaluation">({{\App\Service\CommonService::showFormatNum($data['evalution_count'])}})</a></li>
                    </ul>

                    <ul class="teacher_info flex-ver">
                        <li><img src="{{ \App\Service\CommonService::getUserAvatarUrl($data['senpai']['user_avatar']) }}" class="プロフィールアイコン"></li>
                        <li class="about_teacher">
                            <div
                                @if(!empty($data['senpai']['userConfirm']) &&  $data['senpai']['userConfirm']['pc_state'] == config('const.pers_conf.confirmed')) class="profile_name kakunin_ok"
                                @else class="profile_name kakunin_no"
                                @endif>
                                <p>{{$data['senpai']['name']}}
                                    <span>（{{\App\Service\CommonService::getAge($data['senpai']['user_birthday'])}}）
                                        {{\App\Service\CommonService::getSexStr($data['senpai']['user_sex'])}}</span>
                                </p>
                            </div>
                            <div>
                                <p>{{\App\Service\AreaService::getOneAreaFullName($data['senpai']['user_area_id'])}}</p>
                            </div>
                        </li>
                        <li><p class="orange_link icon_arrow orange_right"><a href="{{route('admin.staff.detail', ['staff' => $data['senpai']['id']])}}">プロフィール</a></p></li>
                    </ul>
                </div>


            </section>

            <section id="info_area">

                <div class="inner_box">
                    <h3>レッスン料金</h3>
                    <div class="white_box base_txt price">
                        <p>@if(isset($data['lesson']['lesson_30min_fees']) && !empty($data['lesson']['lesson_30min_fees']))
                                {{\App\Service\CommonService::showFormatNum($data['lesson']['lesson_30min_fees'])}}
                            @endif
                            円<span> / 30分〜</span>
                        </p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>待ち合わせ場所</h3>
                    <div class="white_box base_txt">
                        <p>{{ $data['lesson']['lesson_area_names'] ? implode('/', $data['lesson']['lesson_area_names']) : '' }}</p>
                        @if(isset($data['lesson']['lesson_pos_detail']) && $data['lesson']['lesson_pos_detail'])
                            <div class="balloon balloon_blue">
                                <p>{{$data['lesson']['lesson_pos_detail']}}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="inner_box">
                    <h3>相談対応エリア</h3>
                    <div class="white_box base_txt">
                        <p>{{ $data['lesson']['lesson_discuss_area_names'] ? implode('/', $data['lesson']['lesson_discuss_area_names']) : '' }}</p>
                        @if(isset($data['lesson']['lesson_discuss_pos_detail']) && $data['lesson']['lesson_discuss_pos_detail'])
                            <div class="balloon balloon_blue">
                                <p>{{$data['lesson']['lesson_discuss_pos_detail']}}</p>
                            </div>
                        @endif

                    </div>
                </div>
                @if(isset($data['lesson_conds']) && !empty($data['lesson_conds']))
                    <div class="inner_box">
                        <h3>こだわり</h3>
                        <div class="kodawari_list">
                            <ul>
                                @foreach($data['lesson_conds'] as $key => $value)
                                    <li>{{$value}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="inner_box">
                    <h3>レッスン内容</h3>
                    <div class="white_box base_txt">
                        <p>
                            @if(isset($data['lesson']['lesson_service_details']) && !empty($data['lesson']['lesson_service_details']))
                                {{$data['lesson']['lesson_service_details']}}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>当日の持ち物・その他</h3>
                    <div class="white_box base_txt">
                        <p>
                            @if(isset($data['lesson']['lesson_other_details']) && !empty($data['lesson']['lesson_other_details']))
                                {{$data['lesson']['lesson_other_details']}}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>予約にあたってのお願い・注意事項</h3>
                    <div class="white_box base_txt">
                        <p>
                            @if(isset($data['lesson']['lesson_buy_and_attentions']) && !empty($data['lesson']['lesson_buy_and_attentions']))
                                {{$data['lesson']['lesson_buy_and_attentions']}}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="inner_box mb-20">
                    <h3>取り消し日時</h3>
                    <div class="white_box base_txt">
                        <p>{{ \Carbon\Carbon::parse($data['lesson']['lesson_stop_cancel_reverse_at'])->format('Y年n月j日 H時i分') }}</p>
                    </div>
                </div>


            </section>

            <div id="footer_button_area" class="under_area">
                <ul>
                    <li class="send-request">
                        <div class="btn_base btn_orange shadow">
                            <button type="button" class="modal-syncer" data-target="btn_cancel_reserve">予約を削除する</button>
                        </div>
                    </li>
                </ul>
            </div>


        {{ Form::close() }}

        @include('admin.layouts.modal-layout', [
            'modal_id'=>"btn_cancel_reserve",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"予約を削除します。<br>よろしいですか?",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"戻る",
        ])

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>


    </style>
@endsection
@section('page_js')
    <script>
        $(document).ready(function() {

        });

        // modal confirm function
        function modalConfirm(modal_id="") {
            // code
            $('#form1').submit();
        }
    </script>
@endsection

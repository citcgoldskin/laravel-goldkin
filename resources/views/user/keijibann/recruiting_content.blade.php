@extends('user.layouts.app')

@section('title', '募集した内容')

@section('content')

    @include('user.layouts.header_under')

    <div id="contents">
            {{ Form::open([ "method"=>"get"]) }}
            <section>

                <div class="inner_box">
                    <h3>カテゴリー</h3>
                    <div class="form_wrap icon_form">
                        <select name="popular" class="category-item" disabled>
                            @foreach($lesson_classes as $key=>$class)
                                <option value="{{$class['class_id']}}" {{$class['class_id'] == old('lesson_classes', isset($lesson_class) ? $lesson_class : '') ? "selected='selected'":''}}>{{$class['class_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>カテゴリーアイコン</h3>
                    <div class="category_img">
                        <p class="c-icon1 active"><img src="{{$class_icon}}" alt="ランニング・ウォーキング"></p>
                    </div>
                </div>
                <div class="inner_box">
                    <h3>募集タイトル</h3>
                    <div class="white_box form_txt">
                        <p>{{$title}}</p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>レッスン開始日時</h3>
                    <div class="white_box form_txt">
                        <p>{{App\Service\TimeDisplayService::getDateFromDatetime($date)}}</p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>レッスン時間</h3>
                    <div class="white_box form_txt">
                        <p>{{$start_hour}}:{{$start_minute}}～{{$end_hour}}:{{$end_minute}}</p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>参加人数</h3>
                    <ul class="select_flex kakunin_select">
                        <li>
                            <div>男性</div>
                            <div class="white_box">
                                <p>{{$count_man}}</p>
                            </div>
                            <div>名</div>
                        </li>
                        <li>
                            <div>女性</div>
                            <div class="white_box">
                                <p>{{$count_woman}}</p>
                            </div>
                            <div>名</div>
                        </li>
                    </ul>
                </div>

                <div class="inner_box">
                    <h3>希望金額</h3>
                    <div class="white_box form_txt">
                        <p>{{App\Service\CommonService::showFormatNum($money_start)}}<small>円～</small>{{App\Service\CommonService::showFormatNum($money_end)}}<small>円</small></p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>レッスン場所</h3>
                    <div class="white_box form_txt">
                        <p>{{$place}}</p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>待ち合わせ場所の詳細（200文字まで）</h3>
                    <div class="white_box form_txt">
                        <p>{{$place_detail}}</p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>募集詳細（1,000文字まで）</h3>
                    <div class="white_box form_txt">
                        <p>
                            {{$recruit_detail}}
                        </p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>希望する性別</h3>
                    <div class="white_box form_txt">
                        <p>{{App\Service\CommonService::getSexStr($sex_hope)}}</p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>希望する年代</h3>
                    <div class="white_box form_txt">
                        <p>{{$age_start.'代'}}～{{$age_end.'代'}}</p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>募集期限</h3>
                    <div class="white_box form_txt">
                        <p>{{ \Carbon\Carbon::parse($recruit_date)->format('Y年m月d日 H時に終了') }}</p>
                    </div>
                </div>

                @if(isset($proposal_senpai) && $proposal_senpai && isset($proposal_senpai->proposalUser) && $proposal_senpai->proposalUser)
                    <div class="inner_box">
                        <h3>あなたが依頼したセンパイ</h3>
                        <div class="white_box form_txt">
                            <div class="lesson_info_area">
                                <ul class="teacher_info_02 mt0">
                                    <li class="icon"><img src="{{\App\Service\CommonService::getUserAvatarUrl($proposal_senpai->proposalUser->user_avatar)}}" class="プロフィールアイコン"></li>
                                    <li class="about_teacher">
                                        <div class="profile_name"><p>{{ $proposal_senpai->proposalUser->name }}<span>（{{ \App\Service\CommonService::getAge($proposal_senpai->proposalUser['user_birthday']) }}）{{ \App\Service\CommonService::getSexStr($proposal_senpai->proposalUser->user_sex) }}</span></p></div>
                                        <div><p class="orange_link icon_arrow orange_right"><a href="{{route('user.myaccount.profile',['user_id'=>$proposal_senpai->proposalUser->id] ) }}">プロフィール</a></p></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="inner_box">
                        <h3>合計金額</h3>
                        <div class="white_box">
                            <ul class="list_box goukei">
                                <li class="due_date">
                                    <div>
                                        <p>
                                            <span>{{ \Carbon\Carbon::parse($proposal_senpai->recruit->rc_date)->format('Y年n月j日') }}</span>
                                            <span>{{ \App\Service\CommonService::getStartAndEndTime($proposal_senpai->pro_start_time, $proposal_senpai->pro_end_time) }}</span>
                                        <p class="price_mark tax-in">{{ number_format($proposal_senpai->pro_money) }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>


                    @if($obj_recruit->rc_stop == config('const.lesson_stop_code.stop_lesson'))
                        <div class="inner_box">
                            <h3>募集期限</h3>
                            <div class="white_box form_txt stop-recruit">
                                <p>
                                    {{ \Carbon\Carbon::parse($obj_recruit->rc_stopped_at)->format('Y年m月d日に公開停止') }}
                                </p>
                                <p class="alert-label">
                                    ※この投稿は運営が不適切と判断したため、公開停止となりました。
                                </p>

                            </div>
                        </div>
                    @endif
                @endif




            </section>

        {{ Form::close() }}

    </div><!-- /contents -->

@endsection

@section('page_css')
    <style>
        .stop-recruit .alert-label {
            color: #FA4712;
        }
    </style>
@endsection


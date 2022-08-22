@extends('user.layouts.app')

@section('title', '掲示板')

<?php $page_id = "board" ?>

@section('content')

    @include('user.layouts.gnavi_under')

    <div id="contents">
        <div class="top-menu_wrap">

            @include('user.layouts.top_menu_location_list', ['from'=>config('const.page_type.keijibann'), 'province_name'=>$province_name])
            <div class="top-menu">

                <section class="tab_area mb0">
                    <div class="switch_tab three_tab">
                        <div class="type_radio radio-01">
                            <input type="radio" name="onof-line" id="off-line" checked="checked" onclick="location.href='{{route("keijibann.list")}}'">
                            <label class="ok" for="off-line">すべて</label>
                        </div>
                        <div class="type_radio radio-02">
                            <input type="radio" name="onof-line" id="on-line-1" onclick="location.href='{{route("keijibann.recruiting")}}/1'">
                            <label class="ok" for="on-line-1">投稿管理</label>
                        </div>
                        <div class="type_radio radio-03">
                            <input type="radio" name="onof-line" id="on-line-2" onclick="location.href='{{route("keijibann.recruiting_proposal")}}/1'">
                            <label class="ok" for="on-line-2">提案管理</label>
                        </div>
                    </div>
                </section>

                <p class="conditions_wrap">
                    　<button type="button" onclick="location.href='{{route('keijibann.condition') . '/' . $total_cnt.'/0'}}'">条件で絞り込む</button>
                </p>
                <nav>
                    <ul class="conditions_box pb20">
                        <li><p class="kensuu result_mark">{{$total_cnt}}</p></li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select id="popular" name="popular" class="sort">
                                    <option value="0" @php echo $search_params['order'] == 0 ? "selected='selected'" : "" ; @endphp >人気順</option>
                                    <option value="1" @php echo $search_params['order'] == 1 ? "selected='selected'" : "" ; @endphp>新着順</option>
                                    <option value="2" @php echo $search_params['order'] == 2 ? "selected='selected'" : "" ; @endphp>古い順</option>
                                    <option value="3" @php echo $search_params['order'] == 3 ? "selected='selected'" : "" ; @endphp>単価の高い順</option>
                                    <option value="4" @php echo $search_params['order'] == 4 ? "selected='selected'" : "" ; @endphp>支払総額の高い順</option>
                                    <option value="5" @php echo $search_params['order'] == 5 ? "selected='selected'" : "" ; @endphp>残り時間の多い順</option>
                                    <option value="6" @php echo $search_params['order'] == 6 ? "selected='selected'" : "" ; @endphp>残り時間の少ない順</option>
                                </select>
                                </select>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>

        <section class="yarukoto_list">

        @foreach($recruits as $key => $val)
                <div class="board_box">
                    <a href="{{ route('keijibann.detail', ['id'=>$val['rc_id']])  }}">
                        <ul class="info_ttl_wrap">
                            <li>
                                <img src="{{\App\Service\CommonService::getLessonIconImgUrl($val['cruitLesson']['class_icon'])}}" alt="">
                            </li>
                            <li>
                                <p class="info_ttl {{ isset($val['is_visited']) && $val['is_visited'] == 1 ? 'navy_txt': 'blue_txt' }}">{{$val['rc_title']}}</p>
                            </li>
                        </ul>
                        <div class="about_detail">
                            <p class="money">{{ \App\Service\CommonService::getLessonMoneyRange($val['rc_wish_minmoney'], $val['rc_wish_maxmoney']) }}<small>{{ \App\Service\CommonService::getTimeUnit($val['rc_lesson_period_from']) }}</small></p>
                            <p class="location">{{ implode('/', $val->recruit_area_names) }}</p>
                            <p class="date_time">
                                <span>{{$val['date']}}</span>
                                <span>{{$val['start_end_time']}}</span>
                            </p>
                        </div>

                        <ul class="teacher_info_03 mb0">
                            <li class="icon_s30"><img src="{{isset($val['cruitUser']) ? \App\Service\CommonService::getUserAvatarUrl($val['cruitUser']['user_avatar']) : ''}}" class="プロフィールアイコン"></li>
                            <li class="about_teacher">
                                <div class="profile_name">
                                    <p>{{isset($val['cruitUser']) ? $val['cruitUser']['name'] : ''}}   <span>（{{isset($val['age']) ? $val['age'] : '' }}）{{$val['sex']}}</span></p>
                                    <p><span>投稿日：{{$val['date_recruit']}}</span></p>
                                </div>
                            </li>
                            <li>
                                @if(Auth::check() && $val['rc_user_id'] != Auth::user()->id)
                                    <div class="c-like-box">
                                        <div class="clex-box_01">
                                            <input type="checkbox" @php if($val['voted'] == 1) {echo "checked='checked'";}; @endphp  name="commitment" id="c{{$val['rc_id']}}">
                                            <label for="c{{$val['rc_id']}}"></label>
                                        </div>
                                    </div>
                                @endif
                            </li>
                        </ul>
                    </a>
                    @if(isset($val['period_futre']) && $val['period_futre'] != "")
                        <span class="time_limit"><small>あと</small>{{$val['period_futre']}}</span>
                    @endif
                </div>
            @endforeach

        </section>

        {{ $pages->appends(['search_params' =>$search_params])->links('vendor.pagination.senpai-pagination') }}

    </div><!-- /contents -->

    <div class="rb_btn_area">
        <a href="{{route('keijibann.recruiting_input')}}">
            <div class="btn_inner">
                <p><img src="{{ asset('assets/user/img/footer/btn_img_listing.png') }}" alt=""></p>
                <p class="post">投稿</p>
            </div>
        </a>
    </div>

    <script type="text/javascript">
        $('input[name="commitment"]').change(function(){
            var bSelected = 0;
            if(this.checked == true)
            {
                bSelected = 1;
            }
            var recruit_id = this.id;

            var form_data = new FormData();
            form_data.append("_token", "{{csrf_token()}}");
            form_data.append("id", recruit_id.replace("c",""));
            form_data.append("bSelected", bSelected);

            $.ajax({
                type: "post",
                url: "{{route('keijibann.postRecruitVote')}}",
                data : form_data,
                dataType: 'json',
                contentType : false,
                processData : false,
                success : function(result) {
                }
            });
        });

        $('#popular').change(function(){
            var orderBy = this.value;
            var form_data = new FormData();
            form_data.append("_token", '{{csrf_token()}}');
            form_data.append("orderBy", orderBy);

            $.ajax({
                type: "post",
                url : '{{route("keijibann.postSetRecruitOrder")}}',
                data : form_data,
                dataType: 'json',
                contentType: false,
                processData : false,
                success : function (result){
                     document.location.href = "{{route('keijibann.list')}}";
                }
            });
        });

    </script>

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection


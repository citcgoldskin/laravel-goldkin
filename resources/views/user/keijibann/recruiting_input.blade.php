@extends('user.layouts.app')

@section('title', '募集内容の入力')

@section('content')

    @include('user.layouts.header_under')

    <div id="contents">
        {{ Form::open(["route" => "keijibann.postRecruitInput", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
        <input type="hidden" name="map_location" id="map_location" value="">
        <section>
            <div class="inner_box">
                <h3>カテゴリー</h3>
                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                    <select name="lesson_classes" id="lesson_classes">
                        <option value="0">選択してください</option>
                        @foreach($lesson_classes as $key=>$class)
                            <option value="{{$class['class_id']}}" {{$class['class_id'] == old('lesson_classes', isset($lesson_class) ? $lesson_class : '') ? "selected='selected'":''}}>{{$class['class_name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('lesson_classes')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="inner_box">
                <h3>カテゴリーアイコン</h3>
                <div class="category_img">
                    <p><img src="{{isset($class_icon)? $class_icon: ''}}" alt="カテゴリーアイコン" id="category_icon"></p>
                </div>
            </div>

            <div class="inner_box">
                <h3>募集タイトル（50文字まで）</h3>
                <div class="input-text2">
                    <textarea placeholder="50文字入ります" cols="50" rows="10" maxlength="50" class="shadow-glay" name="title">{{old('title', isset($title) ? $title : '')}}</textarea>
                    @error('title')
                    <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="inner_box">
                <h3>レッスン開始日時</h3>
                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                    <input type="date"  class="form_btn" value="{{old('date', isset($date) ? $date : date('Y-m-d'))}}" name="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>
                <ul class="time"><li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="start_hour" class="fourth">
                                @for($i = 0; $i < 24; $i++)
                                    <option value="{{$i}}" {{$i == old('start_hour', isset($start_hour) ? $start_hour : '') ? "selected='selected'":''}}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                @endfor
                            </select></div></li>
                    <li>：</li>
                    <li>	 <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="start_minute" class="fourth">
                                <?php $val = "0" ?>
                                <option value="0" {{ $val == old('start_minute', isset($start_minute) ? $start_minute : '') ? "selected='selected'":''}}>00</option>
                                <?php $val = "15" ?>
                                <option value="15" {{ $val == old('start_minute', isset($start_minute) ? $start_minute : '') ? "selected='selected'":''}}>15</option>
                                <?php $val = "30" ?>
                                <option value="30" {{ $val == old('start_minute', isset($start_minute) ? $start_minute : '') ? "selected='selected'":''}}>30</option>
                                <?php $val = "45" ?>
                                <option value="45" {{ $val == old('start_minute', isset($start_minute) ? $start_minute : '') ? "selected='selected'":''}}>45</option>
                            </select></div></li>
                    <li>～</li>
                    <li><div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="end_hour" class="fourth">
                                @for($i = 0; $i < 24; $i++)
                                    <option value="{{$i}}" {{$i == old('end_hour', isset($end_hour) ? $end_hour : 23) ? "selected='selected'":''}}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                @endfor
                            </select></div></li>
                    <li>：</li>
                    <li>	 <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="end_minute" class="fourth">
                                <?php $val = "0" ?>
                                <option value="0" {{ $val == old('end_minute', isset($end_minute) ? $end_minute : '') ? "selected='selected'":''}}>00</option>
                                <?php $val = "15" ?>
                                <option value="15" {{ $val == old('end_minute', isset($end_minute) ? $end_minute : '') ? "selected='selected'":''}}>15</option>
                                <?php $val = "30" ?>
                                <option value="30" {{ $val == old('end_minute', isset($end_minute) ? $end_minute : '') ? "selected='selected'":''}}>30</option>
                                <?php $val = "45" ?>
                                <option value="45" {{ $val == old('end_minute', isset($end_minute) ? $end_minute : '') ? "selected='selected'":''}}>45</option>
                            </select></div></li>
                </ul>
                @error('date')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                @error('start_hour')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                @error('start_minute')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                @error('end_hour')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                @error('end_minute')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="inner_box">
                <h3>レッスン時間</h3>
                <ul class="time2">
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="period_start" class="fourth">
                                <option value="">指定なし</option>
                                @for ($i = 15; $i <= 300; $i+=15)
                                    <option value="{{$i}}"
                                            @if(old('period_start', isset($period_start) ? $period_start : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                        分
                                    </option>
                                @endfor
                            </select>
                        </div>
                        {{--<div class="form_wrap icon_form shadow-glay">
                            <input type="text" name="period_start" value="{{old('period_start', isset($period_start) ? $period_start : '')}}">
                        </div>--}}
                    </li>
                    {{--<li>～</li>
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="period_end" class="fourth">
                                <option value="">指定なし</option>
                                @for ($i = 15; $i <= 300; $i+=15)
                                    <option value="{{$i}}"
                                            @if(old('period_end', isset($period_end) ? $period_end : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                        分
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </li>--}}
                </ul>
                @error('period_start')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                {{--@error('period_end')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror--}}

            </div>

            <div class="inner_box">
                <h3>参加人数</h3>
                <ul class="select_flex flex_w50">
                    <li>
                        <div>男性</div>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select id="area" name="count_man">
                                <option value="0">0</option>
                                @foreach(range(1, 10) as $value)
                                    @php
                                        $val_man = is_object(Auth::user()) && Auth::user()->user_sex == config('const.sex.man') ? 1 : (is_object(Auth::user()) && Auth::user()->user_sex != config('const.sex.man') && Auth::user()->user_sex != config('const.sex.woman') ? 1 : '');
                                        if (isset($count_man)) {
                                            $val_man = $count_man;
                                        }
                                    @endphp
                                    <option value="{{$value}}" {{ old('count_man', $val_man ) == $value ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>名</div>
                    </li>
                    <li>
                        <div>女性</div>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select id="area" name="count_woman">
                                <option value="0">0</option>
                                @foreach(range(1, 10) as $value)
                                    @php
                                        $val_woman = is_object(Auth::user()) && Auth::user()->user_sex == config('const.sex.woman') ? 1 : '';
                                        if (isset($count_woman)) {
                                            $val_woman = $count_woman;
                                        }
                                    @endphp
                                    <option value="{{$value}}" {{ old('count_woman', $val_woman ) == $value ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>名</div>
                    </li>
                </ul>
                <div class="balloon balloon_blue">
                    <p>※募集内容の投稿者は必ず参加してください</p>
                </div>
                @error('count_man')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                @error('count_woman')
                <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </div>

            <div class="inner_box">
                <h3>希望金額</h3>
                <ul class="time2">
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="money_start" class="fourth">
                                <option value="">下限なし</option>
                                @for ($i = 500; $i <= 10000; $i+=500)
                                    <option value="{{$i}}"
                                            @if(old('money_start', isset($money_start) ? $money_start : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                        円
                                    </option>
                                @endfor
                            </select>
                        </div>
                        {{--<div class="form_wrap icon_form shadow-glay">
                            <input type="text" name="money_start" value="{{old('money_start', isset($money_start) ? $money_start : '')}}">
                        </div>--}}
                    </li>
                    <li>～</li>
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="money_end" class="fourth">
                                <option value="">上限なし</option>
                                @for ($i = 500; $i <= 10000; $i+=500)
                                    <option value="{{$i}}"
                                            @if(old('money_end', isset($money_end) ? $money_end : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                        円
                                    </option>
                                @endfor
                            </select>
                        </div>
                        {{--<div class="form_wrap icon_form shadow-glay">
                            <input type="text" name="money_end" value="{{old('money_end', isset($money_end) ? $money_end : '')}}">
                        </div>--}}
                    </li>
                </ul>
                @error('money_start')
                <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
                @error('money_end')
                <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </div>

            <div class="inner_box">
                <h3>レッスン場所</h3>

                <div class="form_wrap icon_form type_search">
                    <input type="hidden" value="{{old('place', isset($place) ? $place : '')}}" placeholder="キーワードで検索" class="search" name="place">
                    {{--longitude--}}<input type="hidden" name="longitude"><br>
                    {{--latitude--}}<input type="hidden" name="latitude"><br>
                    {{--lesson_place--}}<input type="hidden" name="lesson_place"><br>

                    @error('place')
                    <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div id="floating-panel">
                    <input id="latlng" type="textbox" value="">
                </div>

                <div id="map">
                </div>

                <div class="form_wrap icon_form type_search mb30">
                    <input type="text" id="lesson_address_and_keyword" name="lesson_address_and_keyword" value="{{isset($lesson_info) ? $lesson_info['lesson_address_and_keyword'] : ''}}" placeholder="住所やキーワードを入力してください" class="search controls">
                </div>

                @php
                    $lesson_areas = old('map_location', isset($recruit) && is_object($recruit) && $recruit->recruit_area ? $recruit->recruit_area : []);
                @endphp
                <div class="select-wrap mb30">
                    <label>
                        <select class="multi-select-tags" name="tags[]" id="tags" multiple="multiple">
                            @if(is_string($lesson_areas))
                                @php
                                    $lesson_areas = json_decode($lesson_areas);
                                @endphp
                                @if( $lesson_areas && count($lesson_areas) > 0)
                                    @foreach($lesson_areas as $position)
                                        @if($position && isset($position->prefecture))
                                            <option data-lat="{{ $position->lat }}" data-lng="{{ $position->lng }}" data-prefecture="{{ $position->prefecture }}" data-county="{{ $position->county }}" data-locality="{{ $position->locality }}" data-sublocality="{{ $position->sublocality }}" selected>{{ $position->prefecture.$position->county.$position->locality.$position->sublocality }}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <optgroup label="選択されたデータが存在しません。">
                                    </optgroup>
                                @endif
                            @else
                                @if( $lesson_areas && count($lesson_areas) > 0)
                                    @foreach($lesson_areas as $lesson_area)
                                        @php
                                            $position = json_decode($lesson_area['position']);
                                        @endphp
                                        @if($position && $position->prefecture)
                                            <option data-lat="{{ $position->lat }}" data-lng="{{ $position->lng }}" data-prefecture="{{ $position->prefecture }}" data-county="{{ $position->county }}" data-locality="{{ $position->locality }}" data-sublocality="{{ $position->sublocality }}" selected>{{ $position->prefecture.$position->county.$position->locality.$position->sublocality }}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <optgroup label="選択されたデータが存在しません。">
                                    </optgroup>
                                @endif
                            @endif
                        </select>
                        @error('tags')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </label>
                </div>
            </div>

            <div class="inner_box">
                <h3>待ち合わせ場所の詳細（200文字まで）</h3>
                <div class="input-text2">
                    <textarea name="place_detail" placeholder="待ち合わせ場所の詳細を入力してください。" cols="50" rows="10" maxlength="200" class="shadow-glay">{{old('place_detail', isset($place_detail) ? $place_detail : '')}}</textarea>
                    @error('place_detail')
                    <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="inner_box">
                <h3>募集詳細（1,000文字まで）</h3>
                <div class="input-text2">
                    <textarea name="recruit_detail" placeholder="" cols="50" rows="10" maxlength="1000" class="shadow-glay">{{old('recruit_detail', isset($recruit_detail) ? $recruit_detail : '')}}</textarea>
                    @error('recruit_detail')
                    <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="inner_box">
                <h3>希望する性別</h3>
                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                    <select name="sex_hope">
                        <?php $val = "0" ?>
                        <option value="0" {{ $val == old('sex_hope', isset($sex_hope) ? $sex_hope : '') ? "selected='selected'":''}}>指定なし</option>
                        <?php $val = "1" ?>
                        <option value="1" {{ $val == old('sex_hope', isset($sex_hope) ? $sex_hope: '') ? "selected='selected'":''}}>女性</option>
                        <?php $val = "2" ?>
                        <option value="2" {{ $val == old('sex_hope', isset($sex_hope) ? $sex_hope : '') ? "selected='selected'":''}}>男性</option>
                    </select>
                </div>
            </div>

            <div class="inner_box">
                <h3>希望する年代</h3>
                <ul class="select_flex">
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="age_start">
                                <option value="">指定なし</option>
                                @foreach(range(10, 70, 10) as $val)
                                    @php
                                        $age = '';
                                        if (isset($age_start) && $age_start) {
                                            $age = (int)($age_start / 10);
                                            if ($age == 0) {
                                                $age = 10;
                                            } else if($age > 7) {
                                                $age = 70;
                                            } else {
                                                $age = $age * 10;
                                            }
                                        }
                                    @endphp
                                    <option value="{{$val}}" {{ old('age_start', $age) == $val ? 'selected' : '' }}>{{ $val.($val == 70 ? "代以上" : "代") }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>～</div>
                    </li>
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="age_end">
                                <option value="">指定なし</option>
                                @foreach(range(10, 70, 10) as $val)
                                    @php
                                        $age = '';
                                        if (isset($age_end) && $age_end) {
                                            $age = (int)($age_end / 10);
                                            if ($age == 0) {
                                                $age = 10;
                                            } else if($age > 7) {
                                                $age = 70;
                                            } else {
                                                $age = $age * 10;
                                            }
                                        }
                                    @endphp
                                    <option value="{{$val}}" {{ old('age_end', $age) == $val ? 'selected' : '' }}>{{ $val.($val == 70 ? "代以上" : "代") }}</option>
                                @endforeach
                            </select>
                        </div>
                    </li>
                </ul>
                {{--<ul class="time2"><li>
                        <div class="form_wrap icon_form shadow-glay">
                            <input type="text" name="age_start" value="{{old('age_start', isset($age_start) ? $age_start : '')}}">
                        </div>
                    </li>
                    <li>～</li>
                    <li>
                        <div class="form_wrap icon_form shadow-glay">
                            <input type="text" name="age_end" value="{{old('age_end', isset($age_end) ? $age_end : '')}}">
                        </div>
                    </li>
                </ul>--}}
            </div>

            <div class="inner_box">
                <h3>募集期限</h3>
                <ul class="time">
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <input name="recruit_date" type="date"  class="form_btn" value="{{old('recruit_date', isset($recruit_date) ? \Carbon\Carbon::parse($recruit_date)->format('Y-m-d') : '')}}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                    </li>
                    <li class="fs-14">日</li>
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="period_hour" id="period_hour" class="fourth">
                                @for ($i = 0; $i < 24; $i++)
                                    <option value="{{$i}}"
                                            @if(old('period_hour', isset($recruit_date) ? \Carbon\Carbon::parse($recruit_date)->format('H') : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                        </div>
                    </li>
                    <li class="fs-14">時</li>
                </ul>
                @error('recruit_date')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                @error('period_hour')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </section>

        <input type="hidden" name="state" value="2">
        @if(isset($mode) && $mode == "input")
            <div class="bk-white">
                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                            <a onclick="createRecruit('2');">投稿する</a>
                    </div>
                    <div class="btn_base btn_white shadow">
                        <a onclick="createRecruit('0');">下書きに保存</a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="mode" value="input">
            <input type="hidden" name="recruit_id" value="0">
        @elseif(isset($mode) && $mode == "edit")
            <div class="bk-white">
                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <button type="button" class="modal-syncer button-link" data-target="modal-post-edit">{{ isset($recruit) && $recruit->rc_state == config('const.recruit_state.draft') ? "投稿する" : "変更する" }}</button>
                    </div>
                    <div class="btn_base btn_pale-gray shadow">
                        <button type="button" class="modal-syncer button-link" data-target="modal-delete-edit">削除する</button>
                    </div>
                    <div class="btn_base btn_white shadow">
                        <a onclick="createRecruit('0');">下書きに保存</a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="mode" value="edit">
            <input type="hidden" name="recruit_id" value="{{$recruit_id}}">
        @endif
        {{ Form::close() }}

    </div><!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->
    <div class="modal-wrap completion_wrap">
        <div id="modal-post" class="modal-content">

            <div class="modal_body">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        募集を<br>
                        投稿しました
                    </h2>
                </div>
            </div>

            <div class="button-area">
                <div class="btn_base btn_orange">
                    <a id="modal-close" class="button-link">OK</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ********************************************************* -->
    <div class="modal-wrap completion_wrap">
        <div id="modal-shitagaki" class="modal-content">

            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        下書きを<br>
                        保存しました
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

    <div id="modal-post-edit" class="modal-content">
        <div class="modal_body">
            <div class="modal_inner">
                <h4 id="circle-orange_ttl">!</h4>
                <h2 class="modal_ttl">
                    変更してよろしいですか？
                </h2>

                <div class="modal_txt">
                    <p>
                        この変更を行うと<br>
                        現在あなたに提案中のセンパイが<br>
                        全てリセットされます。
                    </p>
                </div>
            </div>

            <div class="button-area">
                <div class="btn_base btn_orange">
                    <button type="button" onclick="createRecruit('2');">{{ isset($recruit) && $recruit->rc_state == config('const.recruit_state.draft') ? "投稿" : "変更" }}</button>
                </div>
                <div class="btn_base btn_gray-line">
                    <a id="modal-close" class="button-link">キャンセル</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ********************************************************* -->
    <div class="modal-wrap completion_wrap">
        <div id="modal-shitagaki2-edit" class="modal-content">

            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        下書きを<br>
                        保存しました
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

    <!-- ********************************************************* -->
    <div class="modal-wrap completion_wrap">
        <div id="modal-delete-edit" class="modal-content">

            <div class="modal_body">
                <div class="modal_inner">
                    <h4 id="circle-orange_ttl">!</h4>
                    <h2 class="modal_ttl">
                        削除してよろしいですか？
                    </h2>

                    <div class="modal_txt">
                        <p>
                            削除を行うと、<br>
                            現在あなたに提案中のセンパイが<br>
                            全てリセットされます。
                        </p>
                    </div>
                </div>

                <div class="button-area">
                    <div class="btn_base btn_orange">
                        <button type="button" onclick="deleteRecruit();">削除</button>

                    </div>
                    <div class="btn_base btn_gray-line">
                        <a id="modal-close" class="button-link">キャンセル</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- モーダル部分 / ここまで ************************************************* -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #F1F9FF;
            border: 1px solid #BCE0FD;
            color: #5BBEEA;
            border-radius: 4px;
            cursor: default;
            float: left;
            margin-right: 5px;
            margin-top: 5px;
            padding: 0 5px;
            font-size: 14px;
        }
        .action-disable {
            opacity: 0.5;
        }
    </style>

@endsection

@section('page_js')
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('const.google_api_key') }}&region=JP&language=ja&callback=initMap&libraries=places" defer></script>
    <script>
        $(document).ready(function (e) {
            $('select#lesson_classes').change(function () {
                setCategoryIcon();
            });

            setCategoryIcon();

            // map

            // 選択された address
            $('.multi-select-tags').select2({
            }).on('select2:opening', function (e) {
                var $self = $(this);
                e.preventDefault();
                $self.select2('close');
            });

            // remove option of map location
            $('.multi-select-tags').on('select2:unselect', function (e) {
                var $self = $(this);
                e.preventDefault();
                $self.select2('close');
                let unselect_text = $(e)[0].params.data.text;
                $('.multi-select-tags').find("option:contains('"+unselect_text+"')").remove();
            });

            navigator.geolocation.getCurrentPosition(function(position) {

                var latitude =  parseFloat(position.coords.latitude);
                //var latitude = 35.6470663;
                var longitude =  parseFloat(position.coords.longitude);
                //var longitude = 139.7094868;

                map = new google.maps.Map(document.getElementById('map'), {
                    center: new google.maps.LatLng(latitude, longitude),
                    zoom: 17
                });

                // ----- search keyworkd start -----

                const input = document.getElementById("lesson_address_and_keyword");
                const searchBox = new google.maps.places.SearchBox(input);

                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                map.addListener("bounds_changed", () => {
                    searchBox.setBounds(map.getBounds());
                });

                let markers = [];

                searchBox.addListener("places_changed", () => {
                    const places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }

                    // Clear out the old markers.
                    markers.forEach((marker) => {
                        marker.setMap(null);
                    });
                    markers = [];

                    // For each place, get the icon, name and location.
                    const bounds = new google.maps.LatLngBounds();

                    places.forEach((place) => {
                        if (!place.geometry || !place.geometry.location) {
                            console.log("Returned place contains no geometry");
                            return;
                        }

                        const icon = {
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(25, 25),
                        };

                        let push_mark = new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        });
                        google.maps.event.addListener(push_mark, 'click', function(event) {
                            geocoder.geocode({
                                'latLng': event.latLng
                            }, function(results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    if (results[0]) {
                                        update_map_location(event, results[0].address_components)
                                    }
                                }
                            });
                        });

                        // Create a marker for each place.
                        markers.push(
                            push_mark
                        );

                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                });

                // ----- search keyworkd end -----

                var geocoder = new google.maps.Geocoder();
                geocoder
                    .geocode({ location: { lat:latitude, lng:longitude} })
                    .then((response) => {
                        if (response.results[0]) {
                            map.setZoom(20);
                            var icon = {
                                scaledSize: new google.maps.Size(30, 32), // scaled size
                                origin: new google.maps.Point(0,0), // origin
                                anchor: new google.maps.Point(0,0) // anchor
                            };
                            if(latitude == "" || longitude == ""){
                                return;
                            }

                            var contentHtml = '<div jstcache="33" class="poi-info-window gm-style">' +
                                '<div jstcache="1">' +
                                '<div jstcache="2" jsinstance="0" class="address-line full-width" jsan="7.address-line,7.full-width">' + response.results[0].formatted_address + '</div>' +
                                '<div jstcache="5" style="display:none"></div>';

                            var myInfo = new google.maps.InfoWindow({
                                content: contentHtml,
                                maxWidth: 350
                            });

                            var marker = new google.maps.Marker({
                                map: map,
                                position: new google.maps.LatLng(latitude, longitude),
                                icon: icon,
                            });

                            google.maps.event.addListener(marker, 'click', function(event) {
                                geocoder.geocode({
                                    'latLng': event.latLng
                                }, function(results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        if (results[0]) {
                                            update_map_location(event, results[0].address_components)
                                        }
                                    }
                                });
                            });

                            // Create the initial InfoWindow.
                            let infoWindow = new google.maps.InfoWindow({
                                content: "Click the map to get Lat/Lng!",
                                position: location,
                            });

                            infoWindow.open(map);

                        } else {
                            window.alert("No results found");
                        }
                    })
                    .catch((e) => window.alert("Geocoder failed due to: " + e));

                // map click event
                google.maps.event.addListener(map, 'click', function(event) {
                    geocoder.geocode({
                        'latLng': event.latLng
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                update_map_location(event, results[0].address_components);
                            }
                        }
                    });
                });
            });
        });


        function createRecruit(kind)
        {
            // map info
            var map_location = [];
            $(".multi-select-tags").each(function() {
                let ele_data = $(this).select2('data');
                for(let i=0;i < ele_data.length; i++) {
                    let ele = ele_data[i].element.dataset;
                    map_location.push({
                        lat: ele.lat,
                        lng: ele.lng,
                        prefecture: ele.prefecture,
                        county: ele.county,
                        locality: ele.locality,
                        sublocality: ele.sublocality
                    });
                }
            });
            $('#map_location').val(JSON.stringify(map_location));

            $('input[name="state"]').val(kind);
            $('#form1').submit();
        }

        function deleteRecruit()
        {
            $('input[name="mode"]').val("delete");
            $('#form1').submit();
        }


        function setCategoryIcon()
        {
            var cat_id = $('select#lesson_classes').val();

            if(cat_id == 0)
            {
                $('.category_img p').hide();
                return;
            }

            var form_data = new FormData();
            form_data.append("_token", "{{csrf_token()}}");
            form_data.append("cat_id", cat_id);

            $.ajax({
                type: "post",
                url: '{{route('keijibann.postClassIcon')}}',
                data : form_data,
                dataType: 'json',
                contentType : false,
                processData : false,
                success : function(result) {
                    if (result.result_code == "success") {
                        if(result.icon_name != "")
                        {
                            $('#category_icon').attr("src", "{{ asset('storage/class_image') }}" + "/" + result.icon_name);
                            $('.category_img p').show();
                        }
                    }
                },
                error : function(error)
                {
                    alert("エラーが発生しました.");
                }
            });

        }

        // map
        function initMap() {

        }

        function update_map_location(event, address_arr) {
            if (address_arr == undefined) {
                return;
            }

            // address 情報取得
            let p_code='', country='', prefecture='', county='', city='', locality='', sublocality='', lat='', lng='';

            for (let i = 0; i < address_arr.length; ++i) {
                if (address_arr[i].types.indexOf("administrative_area_level_1") >= 0 ) {
                    prefecture = address_arr[i].long_name;
                }
                if (address_arr[i].types.indexOf("administrative_area_level_2") >= 0 ) {
                    county = address_arr[i].long_name;
                }
                if (address_arr[i].types.indexOf("locality") >= 0 ) {
                    locality = address_arr[i].long_name;
                }
                if (address_arr[i].types.indexOf("sublocality_level_1") >= 0 ) {
                    sublocality = address_arr[i].long_name;
                }
            }
            let map_location_address = prefecture + county + locality + sublocality;

            // validate 追加
            if (!$('.multi-select-tags').find("option:contains('"+map_location_address+"')").length) {
                let newOption = "<option data-lat='"+event.latLng.lat()+"' data-lng='"+event.latLng.lng()+"' data-prefecture='"+prefecture+"' data-county='"+county+"' data-locality='"+locality+"' data-sublocality='"+sublocality+"' selected>" + map_location_address + "</option>";
                $('.multi-select-tags').append(newOption).trigger('change');
            }
        }

    </script>

@endsection


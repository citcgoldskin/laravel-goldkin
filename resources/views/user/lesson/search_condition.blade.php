@extends('user.layouts.app')

@section('title', '条件で絞り込む')
@php
    use App\Service\CommonService;
@endphp
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')

    @include('user.layouts.header_under')

    <div id="contents">

        {{ Form::open(['route' => ['user.lesson.set_main_search'], "name"=>"form1", "id"=>"form1", "method"=>"post"]) }}
        <section>
            <ul class="form_area sort">
                <li>
                    <div class="form_wrap icon_form type_search">
                        <input type="text" name="title" id="title" value="{{ old('title', isset($search_params['title']) ? $search_params['title'] : '') }}" placeholder="キーワードで検索" class="search">
                    </div>
                </li>

                <li>
                    <h3>都道府県</h3>
                    <div class="form_wrap icon_form type_arrow_right shadow-glay">
                        <button type="button"
                                onClick="location.href='{{route('user.lesson.province', ['prev_url_id' => 3, 'lesson_count' => $lesson_count])}}'"
                                class="form_btn">
                            @if(isset($province_name) && !empty($province_name))
                                {{$province_name}}
                            @else
                                指定なし
                            @endif
                        </button>
                    </div>
                </li>

                <li>
                    <h3>エリア</h3>
                    <div class="form_wrap icon_form type_arrow_right shadow-glay">
                        <button type="button" onClick="location.href='{{route('user.lesson.area', ['province_id' => $province_id])}}'" class="form_btn">
                        {{--<button type="button" class="form_btn">--}}
                            @if(isset($area_name) && !empty($area_name))
                                {{$area_name}}
                            @else
                                指定なし
                            @endif
                        </button>
                    </div>
                </li>

                {{--<li>
                    <h3>レッスン日時</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                        <input type="date" name="date" class="form_btn" value="{{ old('date', isset($search_params['date']) ? $search_params['date'] : '') }}">
                    </div>

                    <ul class="time">
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="start_hour" class="fourth">
                                    @for ($i = 0; $i < 24; $i++)
                                        <option value="{{$i}}"
                                                @if(old('start_hour', isset($search_params['start_hour']) ? $search_params['start_hour'] : 0) == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select></div>
                        </li>
                        <li>：</li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="start_minute" class="fourth">
                                    @for ($i = 0; $i < 60; $i+=15)
                                        <option value="{{$i}}"
                                                @if(old('start_minute', isset($search_params['start_minute']) ? $search_params['start_minute'] : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select></div>
                        </li>

                        <li>～</li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="end_hour" class="fourth">
                                    @for ($i = 0; $i < 24; $i++)
                                        <option value="{{$i}}"
                                                @if(old('end_hour', isset($search_params['end_hour']) ? $search_params['end_hour'] : 23) == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select></div>
                        </li>
                        <li>：</li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="end_minute" class="fourth">
                                    @for ($i = 0; $i < 60; $i+=15)
                                        <option value="{{$i}}"
                                                @if(old('end_minute', isset($search_params['end_minute']) ? $search_params['end_minute'] : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select></div>
                        </li>
                    </ul>
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
                </li>--}}

                <li>
                    <h3>レッスン時間</h3>

                    <ul class="time2">
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="start_interval" class="fourth">
                                    <option value="">指定なし</option>
                                    @for ($i = 15; $i <= 300; $i+=15)
                                        <option value="{{$i}}"
                                                @if(old('start_interval', isset($search_params['start_interval']) ? $search_params['start_interval'] : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                            分
                                        </option>
                                    @endfor
                                </select></div>
                        </li>
                        <li>～</li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="end_interval" class="fourth">
                                    <option value="">指定なし</option>
                                    @for ($i = 15; $i <= 300; $i+=15)
                                        <option value="{{$i}}"
                                                @if(old('end_interval', isset($search_params['end_interval']) ? $search_params['end_interval'] : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                            分
                                        </option>
                                    @endfor
                                </select></div>
                        </li>
                    </ul>
                    @error('start_interval')
                    <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    @error('end_interval')
                    <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>

                <li>
                    <h3>料金</h3>
                    <ul class="time2">
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="start_fee" class="fourth">
                                    <option value="">下限なし</option>
                                    @for ($i = 1000; $i <= 100000; $i+=1000)
                                        <option value="{{$i}}"
                                                @if(old('start_fee', isset($search_params['start_fee']) ? $search_params['start_fee'] : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                            円
                                        </option>
                                    @endfor
                                </select></div>
                        </li>
                        <li>～</li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="end_fee" class="fourth">
                                    <option value="">上限なし</option>
                                    @for ($i = 1000; $i <= 100000; $i+=1000)
                                        <option value="{{$i}}"
                                                @if(old('end_fee', isset($search_params['end_fee']) ? $search_params['end_fee'] : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                            円
                                        </option>
                                    @endfor
                                </select></div>
                        </li>
                    </ul>
                    @error('start_fee')
                    <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    @error('end_fee')
                    <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>

                <li>
                    <h3>性別</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                        <select name="sex" class="fourth">
                            @foreach(config('const.gender_type') as $key=>$value)
                                <option value="{{ $key }}" {{ old('sex', isset($search_params['sex']) ? $search_params['sex'] : '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </li>
                <li>
                    <h3>年代</h3>
                    <ul class="time2">
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="start_age" class="fourth">
                                    <option value="">指定なし</option>
                                    @foreach(range(10, 70, 10) as $val)
                                        @php
                                            $age = '';
                                            if (isset($search_params['start_age']) && $search_params['start_age']) {
                                                $age = (int)($search_params['start_age'] / 10);
                                                if ($age == 0) {
                                                    $age = 10;
                                                } else if($age > 7) {
                                                    $age = 70;
                                                } else {
                                                    $age = $age * 10;
                                                }
                                            }
                                        @endphp
                                        <option value="{{$val}}" {{ old('start_age', $age) == $val ? 'selected' : '' }}>{{ $val.($val == 70 ? "代以上" : "代") }}</option>
                                    @endforeach
                                </select></div>
                        </li>
                        <li>～</li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="end_age" class="fourth">
                                    <option value="">指定なし</option>
                                    @foreach(range(10, 70, 10) as $val)
                                        @php
                                            $age = '';
                                            if (isset($search_params['end_age']) && $search_params['end_age']) {
                                                $age = (int)($search_params['end_age'] / 10);
                                                if ($age == 0) {
                                                    $age = 10;
                                                } else if($age > 7) {
                                                    $age = 70;
                                                } else {
                                                    $age = $age * 10;
                                                }
                                            }
                                        @endphp
                                        <option value="{{$val}}" {{ old('end_age', $age) == $val ? 'selected' : '' }}>{{ $val.($val == 70 ? "代以上" : "代") }}</option>
                                    @endforeach
                                </select></div>
                        </li>
                    </ul>
                    @error('start_age')
                    <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    @error('end_age')
                    <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
            </ul>
            <div class="kodawari_check mt0">
                <h3>こだわり</h3>
                <div class="check-flex">
                    @foreach(\App\Service\LessonService::getLessonConditions() as $key => $value)
                        <div class="clex-box_01 shadow-glay">
                            <input type="hidden" name="condition_{{$key + 1}}" value="0" id="condition_{{$key + 1}}">
                            <input type="checkbox" name="commitment" id="kodawari-c{{$key + 1}}"
                                @if(isset($search_params['condition_'.($key + 1)]) && $search_params['condition_'.($key + 1)] == 1)
                                    value="1" checked="checked"
                                @else
                                    value="0"
                                @endif
                            >
                            <label for="kodawari-c{{$key + 1}}" id="label_{{$key + 1}}" name="cond_label" value="0">
                                <p>{{$value['lc_name']}}</p></label>
                        </div>
                    @endforeach

                </div>
            </div>

        </section>
        {{ Form::close()}}

    </div><!-- /contents-->

    <footer>
        <div id="footer_button_area" class="result">
            <ul>
                <li><span class="kensuu">{{CommonService::showFormatNum($lesson_count)}}</span></li>
                <li>
                    <div class="btn_base btn_white">
                        <button id="submit_btn">検索する</button>
                    </div>
                </li>
            </ul>
        </div>
    </footer>

@endsection

@section('page_js')
<script>
    $("#submit_btn").click(function () {
        $('#form1').submit();
    });

    $('input[name="commitment"]').change(function () {
        if (this.checked == true) {
            this.value = 1;
        } else {
            this.value = 0;
        }

        var i, value;
        for (i = 1; i <= 12; i++) {
            value = $("#kodawari-c" + i).val();
            $("#condition_" + i).val(value);
        }
    });
</script>
@endsection

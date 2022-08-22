@extends('user.layouts.app')

@section('title', '条件で絞り込む')

@section('content')

    @include('user.layouts.header_under')

    <div id="contents">
        @php
            //dd($errors);
        @endphp
        <section>
            {{ Form::open(["route"=>["keijibann.condition_post"], "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
                <ul class="form_area">
                    <li>
                        <div class="form_wrap icon_form type_search">
                            <input name="search_params[keyword]" type="text" value="{{ old('search_params.keyword', isset($search_params['keyword']) ?  $search_params['keyword'] : '') }}" placeholder="キーワードで検索" id="search_keyword" class="search_white">
                        </div>
                    </li>

                    <li>
                        <h3>カテゴリー</h3>
                        <div class="form_wrap icon_form type_arrow_right shadow-glay">
                            <button id="category_id_names" type="button" onClick="location.href='{{route("keijibann.category"). '/' . $tot}}'" class="form_btn">@php echo $cate_names == "" ?  '指定なし' : $cate_names; @endphp </button>
                        </div>
                        <div style="display : none;" id="category_ids">
                            @php
                                if(isset($search_params['category_id']))
                                {
                                    foreach($search_params['category_id'] as $key => $val)
                                    {
                                        echo "<input type='hidden' name='search_params[category_id][]' value='" . $val . "'>";
                                    }
                                }
                            @endphp
                        </div>
                    </li>

                    <li>
                        <h3 class="must">都道府県</h3>
                        <input type="hidden" name="province_id" value="{{ $province_id ? $province_id : '' }}">
                        <div class="form_wrap icon_form type_arrow_right shadow-glay">
                            <button type="button"
                                    onClick="location.href='{{route('keijibann.province', ['prev_url_id' => 4])}}'"
                                    class="form_btn">
                                @if(isset($province_name) && !empty($province_name))
                                    {{$province_name}}
                                @else
                                    ご希望の都道府県を選択してください
                                @endif
                            </button>
                        </div>
                        @error('province_id')
                        <span class="error_text">
                            <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        {{--<h3>都道府県</h3>
                        <div class="form_wrap icon_form type_arrow_right shadow-glay">
                            <select id="area_1" name="search_params[area_id_1]">
                                <option value="0">ご希望の都道府県を選択してください</option>
                                @php
                                foreach($areas as $key => $val)
                                {
                                    if(isset($search_params['area_id_1']) && ($val['area_id'] == $search_params['area_id_1']))
                                    {
                                        echo "<option selected='selected' " . "value=" . "'". $val['area_id'] . "'>" . $val['area_name'] . " </option>";
                                    } else
                                    {
                                        echo "<option " . "value=" . "'". $val['area_id'] . "'>" . $val['area_name'] . "</option>";
                                    }
                                }
                                @endphp
                            </select>
                        </div>--}}
                    </li>

                    <li>
                        <h3>エリア</h3>
                        <input type="hidden" name="area_id_2" value="{{ old('area_id_2', isset($area_name) ? $area_name : '') }}">
                        <div class="form_wrap icon_form type_arrow_right shadow-glay">
                            <button type="button" onClick="location.href='{{route('keijibann.area', ['province_id' => $province_id])}}'" class="form_btn" {{ $province_id ? '' : 'disabled' }}>
                            {{--<button type="button" class="form_btn">--}}
                                @if(isset($area_name) && !empty($area_name))
                                    {{$area_name}}
                                @else
                                    指定なし
                                @endif
                            </button>
                        </div>
                        {{--<h3 class="must">エリア</h3>
                        <div class="form_wrap icon_form type_arrow_right shadow-glay">
                            <select id="area_2" name="area_id_2">
                                <option value="0">指定なし</option>
                                @foreach($area2 as $key => $val)
                                    <option {{($val['area_id'] == $search_params['area_id_2']) || ($val['area_id'] == old('area_id_2', '')) ? "selected='selected'":''}} value="{{$val['area_id']}}">{{$val['area_name']}}</option>
                                @endforeach
                            </select>
                            @error('area_id_2')
                            <span class="error_text">
                            <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>--}}
                    </li>

                    <li>
                        <h3 class="must">レッスン開始日時</h3>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <input type="date" name="date" id="date" class="form_btn" value="{{ old('date', isset($search_params['date']) ? $search_params['date'] : '') }}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            @error('date')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <ul class="time">
                            <li>
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                    <select name="start_hour" class="fourth" id="start_hour">
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
                                                    @if(old('start_minute', isset($search_params['start_minute']) ? $search_params['start_minute'] : 0) == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
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
                                                    @if(old('end_minute', isset($search_params['end_minute']) ? $search_params['end_minute'] : 0) == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
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
                    </li>

                    <li>
                        <h3 class="must">レッスン時間</h3>

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
                        <h3 class="must">料金</h3>
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
                                    </select>
                                </div>
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
                        <h3 class="must">募集期限</h3>
                        {{--<input type="hidden" name="recruit_period_date" id="recruit_period_date" value="">
                        <input type="hidden" name="recruit_period_now" id="recruit_period_now" value="">--}}
                        <input type="hidden" name="recruit_period_time" id="recruit_period_time" value="">
                        @php
                            $current_month = \Carbon\Carbon::now()->format('m');
                            $current_day = \Carbon\Carbon::now()->format('d');
                        @endphp
                        <ul class="time">
                            <li>
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                    <input name="recruit_date" type="date"  class="form_btn" value="{{ old('recruit_date', isset($search_params['recruit_date']) ? $search_params['recruit_date'] : '') }}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                </div>
                            </li>
                            <li class="fs-14">日</li>
                            <li>
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                    <select name="period_hour" id="period_hour" class="fourth">
                                        <option value=""></option>
                                        @for ($i = 0; $i < 24; $i++)
                                            <option value="{{$i}}"
                                                    @if(old('period_hour', isset($search_params['period_hour']) ? $search_params['period_hour'] : '') == $i) selected="selected" @endif>{{ $i }}</option>
                                        @endfor
                                    </select></div>
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
                    </li>

                    <li>
                        <h3>性別</h3>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select id="sex" name="search_params[sex]">
                                @foreach(config('const.gender_type') as $key=>$value)
                                    <option value="{{ $key }}" {{ old('search_params.sex', isset($search_params['sex']) ? $search_params['sex'] : '') == $key ? 'selected' : '' }}>{{ $value }}</option>
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

                    <li>
                        <h3>参加人数</h3>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select id="area" name="search_params[numbers]">
                                <option value="0">指定なし</option>
                                @foreach(range(1, 10) as $value)
                                    <option value="{{$value}}" {{ old('search_params.numbers', isset($search_params['numbers']) ? $search_params['numbers'] : '') == $value ? 'selected' : '' }}>{{ $value }}人</option>
                                @endforeach
                            </select>
                        </div>
                    </li>
                </ul>

                <footer>

                    <div id="footer_button_area" class="result">
                        <ul>
                            <li><span class="kensuu">{{$tot}}</span></li>
                            <li>
                                <div class="btn_base btn_white" id="btn_search"><button type="submit">検索する</button></div>
                            </li>
                        </ul>
                    </div>
                </footer>

            {{ Form::close() }}
        </section>
    </div><!-- /contents -->

    <script type="text/javascript">
        $('#search_keyword').change(function(){

        });

        $('#btn_search').click(function(e) {
            e.preventDefault();
            /*let lesson_start = $('#date').val();
            let period_month = $('#period_month').children("option:selected").text();
            let period_day = $('#period_day').children("option:selected").text();
            let period_hour = $('#period_hour').children("option:selected").text();
            period_month = withZero(period_month);
            if(lesson_start != '' && lesson_start != undefined && period_month !='' && period_day !='' && period_hour !='') {
                $('#recruit_period_date').val(lesson_start.substr(0, 4) + "-" + withZero(period_month) + "-" + withZero(period_day));
                $('#recruit_period_now').val(lesson_start.substr(0, 4) + "-" + withZero(period_month) + "-" + withZero(period_day));
            }*/
            $('#form1').submit();
        });

        $('#area_1').change(function(){
            var area_id = this.value;
            if(area_id == 0)
            {
                $('#area_2').html('<option value="0">指定なし</option>');
                return;
            }

            var form_data = new FormData();
            form_data.append("_token", "{{csrf_token()}}");
            form_data.append("area_id", area_id);

            $.ajax({
                type: "post",
                url: '{{route('keijibann.postGetArea2')}}',
                data : form_data,
                dataType: 'json',
                contentType : false,
                processData : false,
                success : function(result) {
                    if (result.result_code == "success") {
                        var areas = result.areas;
                        var html = '<option value="0">指定なし</option>';
                        for(i = 0; i < areas.length ; i++)
                        {
                            html += '<option value="' + areas[i]['area_id'] + '">' + areas[i]['area_name'] + '</option>'
                        }
                        $('#area_2').html(html);
                    }
                },
                error : function(error)
                {
                    alert("An error occur.");
                }
            });



        });

        function setLeapMonth() {
            var year = $('#date').val();
            let current_month = parseInt("{{ $current_month }}");
            let current_day = parseInt("{{ $current_day }}");
            if (year == "" || year == undefined) return;
            var month_key = '#period_month';
            if($(month_key).children("option:selected").text() =='') return;
            var month =  parseInt($(month_key).children("option:selected").text());
            var leap_year = false;
            if((year % 400==0 || year%100!=0) &&(year%4==0)) {
                leap_year = true;
            }
            var end_day = 31;
            switch (month) {
                case 2:
                    if (leap_year) {
                        end_day = 29;
                    } else {
                        end_day = 28;
                    }
                    break;
                case 4:
                case 6:
                case 9:
                case 11:
                    end_day = 30;
                    break;
            }
            var day_content = '<option value=""></option>';
            var day = parseInt($('#period_day').children("option:selected").text());
            for (let i = 1; i <= end_day; i++) {
                if (day == i) {
                    day_content += '<option value="' + i + '" selected>' + i + '</option>';
                } else {
                    day_content += '<option value="' + i + '">' + i + '</option>';
                }
            }
            $("#period_day").empty().html(day_content);
        }
    </script>

@endsection


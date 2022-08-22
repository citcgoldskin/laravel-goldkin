@extends('admin.layouts.app')
@section('title', '公開予約設定')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.new_question')])

        {{ Form::open(["route"=>"admin.freq.new_question.reserve_create", "method"=>"post", "name"=>"frm_new_question", "id"=>"frm_new_question"]) }}
        <input type="hidden" name="reserve_create" value="1">
        <input type="hidden" name="reserve_date" value="" id="reserve_date">

        <div class="tabs form_page">

            <section>
                <div id="main-contents">
                    <div class="search-result-area">
                        <h2>カテゴリー</h2>
                        <p>{{ \App\Service\QuestionService::getQuestionClassName($params['category']) }}</p>
                        <h2>サブカテゴリー</h2>
                        <p>{{ \App\Service\QuestionService::getQuestionClassName($params['sub_category']) }}</p>
                        <h2>Q.本文</h2>
                        <p>{{ $params['question'] }}</p>
                        <h2>A.本文</h2>
                        <textarea class="no_edit_textarea" readonly>{{ $params['answer'] }}</textarea>
                        <h2>公開日時</h2>
                        <table class="total">
                            <tr>
                                <td><div class="select-box">
                                        <select class="text-left" name="year" id="year" onchange="setLeapMonth('')">
                                            <option value="">--</option>
                                            @foreach(range(\Carbon\Carbon::now()->format('Y') + 5, \Carbon\Carbon::now()->format('Y')-5) as $year)
                                                <option value="{{ $year }}" {{ $year == old('year') ? 'selected' : '' }}>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div></td>
                                <td class="text-center">年</td>
                                <td><div class="select-box">
                                        <select class="text-left" name="month" id="month" onchange="setLeapMonth('birthday')">
                                            <option value="">--</option>
                                            @foreach(range(1, 12) as $month)
                                                <option value="{{ str_pad($month, 2, "0", STR_PAD_LEFT) }}" {{ $month == old('month') ? 'selected' : '' }}>{{ $month }}</option>
                                            @endforeach
                                        </select>
                                    </div></td>
                                <td class="text-center">月</td>
                                <td><div class="select-box">
                                        <select class="text-left" name="day" id="day">
                                            <option value="">--</option>
                                            @foreach(range(1, 31) as $day)
                                                <option value="{{ str_pad($day, 2, "0", STR_PAD_LEFT) }}" {{ $day == old('day') ? 'selected' : '' }}>{{ $day }}</option>
                                            @endforeach
                                        </select>
                                    </div></td>
                                <td class="text-center">日</td>
                            </tr>
                        </table>
                        @error('year')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        @error('month')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        @error('day')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <table class="total">
                            <tr>
                                <td><div class="select-box">
                                        <select name="hour" class="text-left" id="hour">
                                            @for ($i = 0; $i < 24; $i++)
                                                <option value="{{$i}}"
                                                        @if(old('hour') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                            @endfor
                                        </select>
                                    </div></td>
                                <td class="text-center">時</td>
                                <td><div class="select-box">
                                        <select name="minute" class="fourth" id="minute">
                                            @for ($i = 0; $i < 60; $i++)
                                                <option value="{{$i}}"
                                                        @if(old('minute') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                            @endfor
                                        </select>
                                    </div></td>
                                <td class="text-center">分</td>
                            </tr>
                        </table>
                        @error('hour')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        @error('minute')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        @error('reserve_date')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <p id="warning_date_validate" class="error_text hide">公開予約日時を現在時刻以降に設定してください。</p>
                        <p id="warning_reserve_date" class="error_text hide">公開予約日時を正確に入力してください。</p>
                        <div class="btn mtb">
                            <button type="submit" class="finish modal-syncer" data-target="" id="btn_reserve">予約を完了する</button>
                        </div>
                        <div class="btn mtb">
                            <button type="button" onclick="location.href='{{ route('admin.freq.new_question') }}'">戻る</button>
                        </div>
                    </div>
                </div>
            </section>

        </div><!-- /tabs -->

        {{ Form::close() }}

        @include('admin.layouts.modal-layout', [
            'modal_id'=>"modal-caution",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"予約を完了してよろしいですか？",
            'modal_content_area'=>"modal_content_show",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"キャンセル",
        ])

    </div>

@endsection
@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .info_ttl_wrap {
            justify-content: flex-start;
        }
        .tab_item {
            border-bottom: 2px solid #f1f1f1 !important;
            border-top: 2px solid #dad8d6 !important;
        }
        section {
            padding-top: 10px !important;
        }
        .ui-datepicker {
            font-size: 16px;
        }
        h3 {
            font-weight: normal;
        }
        h3.closed {
            margin-bottom: 0px;
        }
        .search-result-area {
            padding: 15px;
            background: white;
        }
        table {
            width: 100%;
        }
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .td-detail {
            text-align: center;
            vertical-align: middle;
            width: 55px;
        }
        .ico-user {
            width: 50px;
            margin-right: 5px;
        }
    </style>
@endsection
@section('page_js')
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/i18n/datepicker-ja.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#btn_reserve').click(function(e) {
                e.preventDefault();

                if(!$('#warning_reserve_date').hasClass('hide')) {
                    $('#warning_reserve_date').addClass('hide');
                }
                if(!$('#warning_date_validate').hasClass('hide')) {
                    $('#warning_date_validate').addClass('hide');
                }

                let year = $('#year').val();
                let month = $('#month').val();
                let day = $('#day').val();
                let hour = $('#hour').val();
                let minute = $('#minute').val();
                if (year == "" || month == "" || day == "") {
                    if($('#warning_reserve_date').hasClass('hide')) {
                        $('#warning_reserve_date').removeClass('hide');
                    }
                    return;
                }
                let date_validate = new Date(year+"-"+month+"-"+day+" "+hour+":"+minute);
                let now = new Date();

                if(date_validate <= now) {
                    if($('#warning_date_validate').hasClass('hide')) {
                        $('#warning_date_validate').removeClass('hide');
                    }
                    return;
                }
                return;
                /*$('#reserve_date').val(year+"-"+month+"-"+day+" "+hour+":"+minute);
                $('#frm_new_question').submit();*/
            });

            $('#year, #month, #day, #hour, #minute').change(function() {
                let year = $('#year').val();
                let month = $('#month').val();
                let day = $('#day').val();
                let hour = $('#hour').val();
                let minute = $('#minute').val();
                if (year != "" && month != "" && day != "") {
                    let date_validate = new Date(year+"-"+month+"-"+day+" "+hour+":"+minute);
                    let now = new Date();
                    if(date_validate > now) {
                        $('#btn_reserve').attr('data-target', "modal-caution");
                    } else {
                        $('#btn_reserve').attr('data-target', "");
                    }
                } else {
                    $('#btn_reserve').attr('data-target', "");
                }

                let msg = '<strong>公開日時</strong><br><br>' + year + '年' + month + '月' + day + '日' + hour + '時' + minute + '分';
                $('#modal_content_show').empty().html(msg);
            });
        });

        function modalConfirm(modal_id="") {
            // code
            if(modal_id == "modal-caution") {
                let year = $('#year').val();
                let month = $('#month').val();
                let day = $('#day').val();
                let hour = $('#hour').val();
                let minute = $('#minute').val();
                $('#reserve_date').val(year+"-"+month+"-"+day+" "+hour+":"+minute);
                $('#frm_new_question').submit();
            }
        }

        function setLeapMonth() {
            var year_key = '#year';
            var month_key = '#month';

            if($(year_key).children("option:selected").text() =='' || $(month_key).children("option:selected").text() =='') return;
            var year = parseInt($(year_key).children("option:selected").text());
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
            for (let i = 1; i <= end_day; i++) {
                day_content += '<option value="' + i + '">' + i + '</option>';
            }
            $("#day").empty().html(day_content);
        }

    </script>
@endsection

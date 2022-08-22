@extends('admin.layouts.app')

@section('title', '非公開設定')

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <div class="under-title">
                <button type="button" onclick="location.href='{{ route('admin.news.detail', ['news'=>$record->id]) }}'">＜</button>
                <label class="page-title" style="width: 100%;">非公開設定</label>
            </div>
            <section>
                {{ Form::open(["route"=> ["admin.news.update", ['news'=>$record->id]], "method"=>"post", "name"=>"frm_news_publish", "id"=>"frm_news_publish"]) }}

                @php
                    $c_year = Carbon::now()->format('Y');

                    $n_publish_year = old('n_publish_year', isset($record['n_publish_year']) ? $record['n_publish_year'] : '');
                    $n_publish_month = old('n_publish_month', isset($record['n_publish_month']) ? $record['n_publish_month'] : '');
                    $n_publish_day = old('n_publish_day', isset($record['n_publish_day']) ? $record['n_publish_day'] : '');
                    $n_publish_hour = old('n_publish_hour', isset($record['n_publish_hour']) ? $record['n_publish_hour'] : '');
                    $n_publish_minute = old('n_publish_minute', isset($record['n_publish_minute']) ? $record['n_publish_minute'] : '');
                    $status = old('status', isset($record['status']) ? $record['status'] : '');

                @endphp
                <div id="main-contents">
                        <div class="search-result-area">
                            <!-- -->
                            <div class="Accordion">
                                <ul class="faq-list normal-list no-flex">
                                    <li><div class="Item">
                                            <input type="radio" name="status" id="checkbox-ac1" value="{{ config('const.news_status_code.n_publish') }}" {{ $status == config('const.news_status_code.n_publish') ? 'checked' : '' }}>
                                            <label for="checkbox-ac1">
                                                <div class="AccordionItem">今すぐ非公開にする</div>
                                                <div class="AccordionPanel">
                                                    <div><p>すぐに当該お知らせを非公開にします。</p> </div>
                                                </div>
                                            </label>
                                        </div></li>
                                    <li><div class="Item">
                                            <input type="radio" name="status" id="checkbox-ac2" value="{{ config('const.news_status_code.limit_n_publish') }}" {{ $status == config('const.news_status_code.limit_n_publish') ? 'checked' : '' }}>
                                            <label for="checkbox-ac2">
                                                <div class="AccordionItem">日時を定めて非公開にする</div>
                                                <div class="AccordionPanel">
                                                    <div>

                                                        <table class="total">
                                                                <tbody><tr>
                                                                    <td>
                                                                        <div class="select-box">
                                                                            <select class="text-left" name="n_publish_year" id="n_publish_year" onchange="setLeapMonth('n_publish')">
                                                                                @foreach(range($c_year, $c_year+5) as $year)
                                                                                    <option value="{{ $year }}" {{ $n_publish_year == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center">年</td>
                                                                    <td><div class="select-box">
                                                                            <select class="text-left" name="n_publish_month" id="n_publish_month" onchange="setLeapMonth('n_publish')">
                                                                                <option value="">--</option>
                                                                                @foreach(range(1, 12) as $month)
                                                                                    <option value="{{ $month }}" {{ $n_publish_month == $month ? 'selected' : '' }}>{{ $month }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div></td>
                                                                    <td class="text-center">月</td>
                                                                    <td><div class="select-box">
                                                                            <select class="text-left" name="n_publish_day" id="n_publish_day">
                                                                                <option value="">--</option>
                                                                            </select>
                                                                        </div></td>
                                                                    <td class="text-center">日</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        <table class="total">
                                                                <tbody><tr>
                                                                    <td><div class="select-box">
                                                                            <select class="text-left" name="n_publish_hour" id="n_publish_hour">
                                                                                <option value="">--</option>
                                                                                @foreach(range(0, 23) as $hour)
                                                                                    @php $hour = str_pad($hour, 2, '0', STR_PAD_LEFT) @endphp
                                                                                <option value="{{ $hour }}" {{ $n_publish_hour == $hour ? 'selected' : '' }}>{{ $hour }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div></td>
                                                                <td class="text-center">時</td>
                                                                <td><div class="select-box">
                                                                        <select class="text-left" name="n_publish_minute" id="n_publish_minute">
                                                                            <option value="">--</option>
                                                                            @foreach(range(0, 59) as $minute)
                                                                                @php $minute = str_pad($minute, 2, '0', STR_PAD_LEFT) @endphp
                                                                                <option value="{{ $minute }}" {{ $n_publish_minute == $minute ? 'selected' : '' }}>{{ $minute }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div></td>
                                                                <td class="text-center">分</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>

                                                        <p>に非公開にする</p></div>
                                                        @error('n_publish_year')
                                                                <span  class="error_text">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                        @enderror
                                                </div>
                                            </label>
                                        </div>

                                    </li>
                                </ul>
                                @error('status')
                                <span  class="error_text">
                                         <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>

                            <div class="btn mtb">
                                <button type="submit" class="ok" name="status_type" value="private">決定する</button>
                            </div>
                            <div class="btn mtb">
                                <button type="button" onclick="location.href='{{ route('admin.news.detail', ['news'=>$record->id]) }}'">戻る</button>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
            </section>
        </div>
        <!-- /tabs -->

    </div>
@endsection

@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <style>
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
        .profile {
            cursor: pointer;
        }
    </style>
@endsection

@section('page_js')
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            setLeapMonth('n_publish', '{{ $n_publish_day }}');

        });
        function setLeapMonth(type, value='') {
            let year_key = '#' + type + '_year';
            let month_key = '#' + type + '_month';
            let day_key = '#' + type + '_day';

            if($(year_key).children("option:selected").text() =='' || $(month_key).children("option:selected").text() =='') return;
            let year = parseInt($(year_key).children("option:selected").text());
            let month =  parseInt($(month_key).children("option:selected").text());
            let leap_year = false;
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
            let day_content = '<option value="">--</option>';
            for (let i = 1; i <= end_day; i++) {
                day_content += '<option value="' + i + '" ' + (i==value ? 'selected' : '') + '>' + i + '</option>';
            }
            $(day_key).empty().html(day_content);
        }
    </script>
@endsection

@extends('admin.layouts.app')

@section('title', '公開設定')

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <div class="under-title">
                @if(is_object($obj_news))
                    <button type="button" onclick="location.href='{{ route('admin.news.edit', ['news' => $obj_news->id]) }}'">＜</button>
                @else
                    <button type="button" onclick="location.href='{{ route('admin.news.create') }}'">＜</button>
                @endif
                <label class="page-title" style="width: 100%;">公開設定</label>
            </div>
            <section>
                @if(is_object($obj_news))
                    {{ Form::open(["route"=> ["admin.news.update", ['news'=>$obj_news->id]], "method"=>"post", "name"=>"frm_news_publish", "id"=>"frm_news_publish"]) }}
                @else
                    {{ Form::open(["route"=> ["admin.news.store"], "method"=>"post", "name"=>"frm_news_publish", "id"=>"frm_news_publish"]) }}
                @endif

                @php
                    $c_year = Carbon::now()->format('Y');

                    $publish_year = old('publish_year', isset($record['publish_year']) ? $record['publish_year'] : '');
                    $publish_month = old('publish_month', isset($record['publish_month']) ? $record['publish_month'] : '');
                    $publish_day = old('publish_day', isset($record['publish_day']) ? $record['publish_day'] : '');
                    $publish_hour = old('publish_hour', isset($record['publish_hour']) ? $record['publish_hour'] : '');
                    $publish_minute = old('publish_minute', isset($record['publish_minute']) ? $record['publish_minute'] : '');
                    $status = old('status', isset($record['status']) ? $record['status'] : '');

                @endphp
                <div id="main-contents">
                        <div class="search-result-area">
                            <!-- -->
                            <div class="Accordion">
                                <ul class="faq-list normal-list no-flex">
                                    <li><div class="Item">
                                            <input type="radio" name="status" id="checkbox-ac1" value="{{ config('const.news_status_code.publish') }}" {{ $status == config('const.news_status_code.publish') ? 'checked' : '' }}>
                                            <label for="checkbox-ac1">
                                                <div class="AccordionItem">今すぐ公開にする</div>
                                                <div class="AccordionPanel">
                                                    <div><p>すぐに当該お知らせを公開にします。</p> </div>
                                                </div>
                                            </label>
                                        </div></li>
                                    <li><div class="Item">
                                            <input type="radio" name="status" id="checkbox-ac2" value="{{ config('const.news_status_code.limit_publish') }}" {{ $status == config('const.news_status_code.limit_publish') ? 'checked' : '' }}>
                                            <label for="checkbox-ac2">
                                                <div class="AccordionItem">日時を定めて公開にする</div>
                                                <div class="AccordionPanel">
                                                    <div>

                                                        <table class="total">
                                                                <tbody><tr>
                                                                    <td>
                                                                        <div class="select-box">
                                                                            <select class="text-left" name="publish_year" id="publish_year" onchange="setLeapMonth('publish')">
                                                                                @foreach(range($c_year, $c_year+5) as $year)
                                                                                    <option value="{{ $year }}" {{ $publish_year == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center">年</td>
                                                                    <td><div class="select-box">
                                                                            <select class="text-left" name="publish_month" id="publish_month" onchange="setLeapMonth('publish')">
                                                                                <option value="">--</option>
                                                                                @foreach(range(1, 12) as $month)
                                                                                    <option value="{{ $month }}" {{ $publish_month == $month ? 'selected' : '' }}>{{ $month }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div></td>
                                                                    <td class="text-center">月</td>
                                                                    <td><div class="select-box">
                                                                            <select class="text-left" name="publish_day" id="publish_day">
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
                                                                            <select class="text-left" name="publish_hour" id="publish_hour">
                                                                                <option value="">--</option>
                                                                                @foreach(range(0, 23) as $hour)
                                                                                    @php $hour = str_pad($hour, 2, '0', STR_PAD_LEFT) @endphp
                                                                                <option value="{{ $hour }}" {{ $publish_hour == $hour ? 'selected' : '' }}>{{ $hour }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div></td>
                                                                <td class="text-center">時</td>
                                                                <td><div class="select-box">
                                                                        <select class="text-left" name="publish_minute" id="publish_minute">
                                                                            <option value="">--</option>
                                                                            @foreach(range(0, 59) as $minute)
                                                                                @php $minute = str_pad($minute, 2, '0', STR_PAD_LEFT) @endphp
                                                                                <option value="{{ $minute }}" {{ $publish_minute == $minute ? 'selected' : '' }}>{{ $minute }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div></td>
                                                                <td class="text-center">分</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>

                                                        <p>に公開にする</p></div>
                                                        @error('publish_year')
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
                                <button type="submit" class="ok" name="status_type" value="publish">決定する</button>
                            </div>
                            <div class="btn mtb">
                                @if(is_object($obj_news))
                                    <button type="button" onclick="location.href='{{ route('admin.news.edit', ['news'=>$obj_news->id]) }}'">戻る</button>
                                @else
                                    <button type="button" onclick="location.href='{{ route('admin.news.create') }}'">戻る</button>
                                @endif
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
            setLeapMonth('publish', '{{ $publish_day }}');

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

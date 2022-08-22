<div id="main-contents">
    <div class="search-result-area">
        @php
            $c_year = Carbon::now()->format('Y');

            $start_year = old('start_year', isset($maintenance['start_year']) ? $maintenance['start_year'] : '');
            $start_month = old('start_month', isset($maintenance['start_month']) ? $maintenance['start_month'] : '');
            $start_day = old('start_day', isset($maintenance['start_day']) ? $maintenance['start_day'] : '');
            $start_hour = old('start_hour', isset($maintenance['start_hour']) ? $maintenance['start_hour'] : '');
            $start_minute = old('start_minute', isset($maintenance['start_minute']) ? $maintenance['start_minute'] : '');
            $end_year = old('end_year', isset($maintenance['end_year']) ? $maintenance['end_year'] : '');
            $end_month = old('end_month', isset($maintenance['end_month']) ? $maintenance['end_month'] : '');
            $end_day = old('end_day', isset($maintenance['end_day']) ? $maintenance['end_day'] : '');
            $end_hour = old('end_hour', isset($maintenance['end_hour']) ? $maintenance['end_hour'] : '');
            $end_minute = old('end_minute', isset($maintenance['end_minute']) ? $maintenance['end_minute'] : '');
            $notice_year = old('notice_year', isset($maintenance['notice_year']) ? $maintenance['notice_year'] : '');
            $notice_month = old('notice_month', isset($maintenance['notice_month']) ? $maintenance['notice_month'] : '');
            $notice_day = old('notice_day', isset($maintenance['notice_day']) ? $maintenance['notice_day'] : '');
            $notice_hour = old('notice_hour', isset($maintenance['notice_hour']) ? $maintenance['notice_hour'] : '');
            $notice_minute = old('notice_minute', isset($maintenance['notice_minute']) ? $maintenance['notice_minute'] : '');
            $services = old('services', isset($maintenance['services']) ? $maintenance['services'] : []);
        @endphp
        <h3>開始</h3>
        <table class="total">
            <tbody><tr>
                <td>
                    <div class="select-box">
                        <select class="text-left" name="start_year" id="start_year" onchange="setLeapMonth('start')">
                            @foreach(range($c_year, $c_year+5) as $year)
                                <option value="{{ $year }}" {{ $start_year == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td class="text-center">年</td>
                <td><div class="select-box">
                        <select class="text-left" name="start_month" id="start_month" onchange="setLeapMonth('start')">
                            <option value="">--</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ $start_month == $month ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div></td>
                <td class="text-center">月</td>
                <td><div class="select-box">
                        <select class="text-left" name="start_day" id="start_day">
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
                        <select class="text-left" name="start_hour" id="start_hour">
                            <option value="">--</option>
                            @foreach(range(0, 23) as $hour)
                                @php $hour = str_pad($hour, 2, '0', STR_PAD_LEFT) @endphp
                                <option value="{{ $hour }}" {{ $start_hour == $hour ? 'selected' : '' }}>{{ $hour }}</option>
                            @endforeach
                        </select>
                    </div></td>
                <td class="text-center">時</td>
                <td><div class="select-box">
                        <select class="text-left" name="start_minute" id="start_minute">
                            <option value="">--</option>
                            @foreach(range(0, 59) as $minute)
                                @php $minute = str_pad($minute, 2, '0', STR_PAD_LEFT) @endphp
                                <option value="{{ $minute }}" {{ $start_minute == $minute ? 'selected' : '' }}>{{ $minute }}</option>
                            @endforeach
                        </select>
                    </div></td>
                <td class="text-center">分</td>
            </tr>
            </tbody>
        </table>
        @error('start_year')
            <span  class="error_text">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <h3 class="mt10">終了</h3>
        <table class="total">
            <tbody><tr>
                <td>
                    <div class="select-box">
                        <select class="text-left" name="end_year" id="end_year" onchange="setLeapMonth('end')">
                            @foreach(range($c_year, $c_year+5) as $year)
                                <option value="{{ $year }}" {{ $end_year == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td class="text-center">年</td>
                <td><div class="select-box">
                        <select class="text-left" name="end_month" id="end_month" onchange="setLeapMonth('end')">
                            <option value="">--</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ $end_month == $month ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div></td>
                <td class="text-center">月</td>
                <td><div class="select-box">
                        <select class="text-left" name="end_day" id="end_day">
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
                        <select class="text-left" name="end_hour" id="end_hour">
                            <option value="">--</option>
                            @foreach(range(0, 23) as $hour)
                                @php $hour = str_pad($hour, 2, '0', STR_PAD_LEFT) @endphp
                                <option value="{{ $hour }}" {{ $end_hour == $hour ? 'selected' : '' }}>{{ $hour }}</option>
                            @endforeach
                        </select>
                    </div></td>
                <td class="text-center">時</td>
                <td><div class="select-box">
                        <select class="text-left" name="end_minute" id="end_minute">
                            <option value="">--</option>
                            @foreach(range(0, 59) as $minute)
                                @php $minute = str_pad($minute, 2, '0', STR_PAD_LEFT) @endphp
                                <option value="{{ $minute }}" {{ $end_minute == $minute ? 'selected' : '' }}>{{ $minute }}</option>
                            @endforeach
                        </select>
                    </div></td>
                <td class="text-center">分</td>
            </tr>
            </tbody>
        </table>
        @error('end_year')
            <span  class="error_text">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <div class="bb pb10"></div>
        <h2>対象サービス</h2>
        <ul class="faq-list normal-list">
            @foreach(config('const.maintenance_services') as $key => $name)
                <li><input type="checkbox" name="services[]" id="service_{{ $key }}" value="{{ $key }}" {{ in_array($key, $services) ? 'checked' : '' }}><label for="service_{{ $key }}">{{ $name }}</label></li>
            @endforeach
        </ul>
        @error('services')
            <span  class="error_text mt10">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <div class="bb pb10"></div>
        <h2>通知日時</h2>
        <table class="total">
            <tbody><tr>
                <td>
                    <div class="select-box">
                        <select class="text-left" name="notice_year" id="notice_year" onchange="setLeapMonth('notice')">
                            @foreach(range($c_year, $c_year+5) as $year)
                                <option value="{{ $year }}" {{ $notice_year == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td class="text-center">年</td>
                <td><div class="select-box">
                        <select class="text-left" name="notice_month" id="notice_month" onchange="setLeapMonth('notice')">
                            <option value="">--</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ $notice_month == $month ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div></td>
                <td class="text-center">月</td>
                <td><div class="select-box">
                        <select class="text-left" name="notice_day" id="notice_day">
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
                        <select class="text-left" name="notice_hour" id="notice_hour">
                            <option value="">--</option>
                            @foreach(range(0, 23) as $hour)
                                @php $hour = str_pad($hour, 2, '0', STR_PAD_LEFT) @endphp
                                <option value="{{ $hour }}" {{ $notice_hour == $hour ? 'selected' : '' }}>{{ $hour }}</option>
                            @endforeach
                        </select>
                    </div></td>
                <td class="text-center">時</td>
                <td><div class="select-box">
                        <select class="text-left" name="notice_minute" id="notice_minute">
                            <option value="">--</option>
                            @foreach(range(0, 59) as $minute)
                                @php $minute = str_pad($minute, 2, '0', STR_PAD_LEFT) @endphp
                                <option value="{{ $minute }}" {{ $notice_minute == $minute ? 'selected' : '' }}>{{ $minute }}</option>
                            @endforeach
                        </select>
                    </div></td>
                <td class="text-center">分</td>
            </tr>
            </tbody>
        </table>
        @error('notice_year')
            <span  class="error_text">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <div class="btn mtb">
            <button type="submit" onclick="location.href='new-confirm.php'">設定する</button>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        setLeapMonth('start', '{{ $start_day }}');
        setLeapMonth('end', '{{ $end_day }}');
        setLeapMonth('notice', '{{ $notice_day }}');
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

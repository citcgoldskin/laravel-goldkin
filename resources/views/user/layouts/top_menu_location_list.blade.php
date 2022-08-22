<div class="top-menu area_base">
    <div class="display_area">
        @if(isset($from) && $from == config('const.page_type.keijibann'))
            {{--<a href="{{route('keijibann.province', ['prev_url_id'=>4])}}">--}}
            <a href="" onclick="event.preventDefault();" style="cursor: unset">
        @else
            {{--<a href="{{route('user.lesson.province', ['prev_url_id'=>3, 'lesson_count'=>isset($lesson_count) ? $lesson_count : 0])}}">--}}
            <a href="" onclick="event.preventDefault();" style="cursor: unset">
        @endif
                <p class="">{{ isset($province_name) && $province_name ? $province_name : 'すべて' }}</p>
            </a>
    </div>
</div>

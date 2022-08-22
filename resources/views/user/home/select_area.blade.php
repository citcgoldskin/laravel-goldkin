@extends('user.layouts.app')
@section('title', '都道府県を選択')

@section('content')
    @include('user.layouts.header_under')

    <div id="contents">

        <!--main_-->
        {{ Form::open(["route"=>"lesson_area", "method"=>"get", "name"=>"form1", "id"=>"form1"]) }}
            <section>

                <!-- ************************************************************ -->
                {{--<ul class="top-pref">
                    @foreach( \App\Service\AreaService::getTopAreaList() as $key => $value )
                        <li>
                            <input type="submit" value="{{ $value['area_name'] }}" name="{{ $value['area_id'] }}">
                        </li>
                    @endforeach
                </ul>--}}

                <input type="hidden" value="" name="area_id" id="area_id">
                <input type="hidden" value="" name="province_id" id="province_id">
                <input type="hidden" value="" name="area_name" id="area_name">
                @foreach(\App\Service\AreaService::getRegionAndPrefectures() as $region_id => $region_data)
                    <div class="board_box set-list_wrap">
                        <input id="pref-{{$region_id}}" name="acd" class="acd-check" type="checkbox">
                        <label class="acd-label" for="pref-{{$region_id}}">{{ $region_data['region'] }}</label>
                        <div class="acd-content set-list_content">
                            <ul>
                                @foreach($region_data['child'] as $pref_id => $pref_name)
                                    <li>
                                        <a href="" class="btn_area" data-area="{{ $pref_name }}" data-id="{{ $region_id }}" data-province="{{ $pref_id }}">
                                            {{ $pref_name }}
                                            <span></span>
                                        </a>
                                        {{--<input type="hidden" value="{{ $pref_name }}" name="{{ $pref_id }}">--}}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach

            </section>

        {{ Form::close() }}

    </div><!-- /contents -->

@endsection

@section('page_js')
    <script>
        $(document).ready(function() {
            $('.btn_area').click(function(e) {
                e.preventDefault();
                let area_id = $(this).attr('data-id');
                let province_id = $(this).attr('data-province');
                let area_name = $(this).attr('data-area');
                console.log("area_id", area_id);
                $('#area_id').val(area_id);
                $('#province_id').val(province_id);
                $('#area_name').val(area_name);
                $('#form1').submit();
            });
        });
    </script>
@endsection




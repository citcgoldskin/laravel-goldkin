@extends('user.layouts.app')
@section('title', '希望エリア選択')

@section('content')

    <p class="back"> <a href="#" onclick="history.back(-1);return false;">＜</a></p>
    <div>
        <!--startup-->
        <div id="startup" class="lesson_area">
            <div class="top_wrap">
                <div class="logo"><img src="{{ asset('assets/user/img/logo2.svg') }}" alt="センパイロゴ"></div>

                {{ Form::open(["route"=>["lesson_area"], "method"=>"get"]) }}
                    <ul class="form_area">
                        <li>
                            <h3 class="title-lesson">レッスン希望エリア</h3>
                            <div class="form_wrap icon_form type_arrow_right shadow-glay w736 for-warning">
                                <button type="button" onclick="location.href='{{ route('select_area') }}'" class="form_btn f14">
                                    @if ( $area_name == '' )
                                        ご希望の都道府県を選択してください
                                    @else
                                        {{ $area_name }}
                                    @endif
                                </button>
                                <p class="warning"></p>
                            </div>
                        </li>
                    </ul>

                    <div class="top_msg">
                        「センパイをはじめる」をタップすると<br>
                        <a href="{{ route('using_rules') }}">利用規約</a>および<a href="{{ route('privacy_policy') }}">プライバシーポリシー</a>に同意したことになります。　
                    </div>

                {{ Form::close() }}

                <div class="button-area" id="button-area">
                    <div class="btn_base btn_orange brown_filter {{ $area_name != '' ? '' : 'btn-gray' }}"><a href="{{ route('home', ['area_id' => $area_id, 'province_id' => $province_id ]) }}" class="{{ $area_name != '' ? '' : 'action-disable' }}">センパイをはじめる</a></div>
                </div>

            </div>
        </div>
        <!--startup end-->

    </div><!-- /contents-->

    <style>
        .btn-gray {
            background: #aaa !important;
        }
    </style>
@endsection

@section('page_js')
    <script src="{{ asset('assets/user/js/validate.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.btn-gray').click(function() {
                addError($(".form_btn"), "※都道府県を選択してください");
            });
        });
    </script>
@endsection


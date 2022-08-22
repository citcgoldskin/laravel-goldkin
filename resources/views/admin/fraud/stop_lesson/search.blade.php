@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        <section>
            {{ Form::open(["route"=>["admin.fraud_stop_lesson.do_search"], "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
            <ul class="form_area">

                <li>
                    <h3>カテゴリー</h3>
                    <div class="form_wrap icon_form type_arrow_right shadow-glay">
                        <button id="category_id_names" type="button" class="form_btn">指定なし</button>
                    </div>
                </li>

                <li>
                    <h3>エリア</h3>
                    <input type="hidden" name="area_id_2" value="">
                    <div class="form_wrap icon_form type_arrow_right shadow-glay">
                        <button type="button" class="form_btn">
                            指定なし
                        </button>
                    </div>
                </li>

                <li>
                    <h3>期間</h3>
                    <div class="flex">
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay mr-20">
                            <input type="date" name="from_date" id="from_date" class="form_btn" value="">
                        </div>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <input type="date" name="to_date" id="to_date" class="form_btn" value="">
                        </div>
                    </div>
                </li>

                <li>
                    <h3>ID</h3>
                    <div class="form_wrap icon_form shadow-glay">
                        <input type="text" name="user_id" id="user_id" class="form_btn" value="">
                    </div>
                </li>

                <li>
                    <h3>ニックネーム</h3>
                    <div class="form_wrap icon_form shadow-glay">
                        <input type="text" name="nickname" id="nickname" class="form_btn" value="">
                    </div>
                </li>

                <li>
                    <h3>ぴろしきまるのみ表示</h3>
                    <div class="form_wrap icon_form shadow-glay type_arrow_bottom">
                        <select name="search_params[user_sex]" id="user_sex">
                            <option value="">--</option>
                            @foreach(config('const.hirosikimaru_show') as $k => $v)
                                @if($k)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </li>


            </ul>

            <footer>

                <div id="footer_button_area" class="result">
                    <ul>
                        <li>
                            <div class="btn_base btn_white" id="btn_search"><button type="submit">検索する</button></div>
                        </li>
                    </ul>
                </div>
            </footer>

            {{ Form::close() }}
        </section>

    </div>
@endsection
@section('page_css')
    <style>
        #footer_button_area ul {
            justify-content: center;
        }
    </style>
@endsection
@section('page_js')
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection

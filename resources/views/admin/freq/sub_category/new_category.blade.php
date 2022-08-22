@extends('admin.layouts.app')
@section('title', '新規追加')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.sub_category.index', ['category'=>$category_id])])

        {{ Form::open(["route"=>"admin.freq.sub_category.add_category", "method"=>"post", "name"=>"frm_add_category", "id"=>"frm_add_category"]) }}
        <input type="hidden" name="category_id" value="{{ $category_id }}">

        <div class="tabs form_page">

            <section>
                @include('admin.layouts.flash-message')
                <div id="main-contents">
                    <div class="search-result-area">
                        <h2>サブカテゴリー</h2>
                        <ul class="btn-list mt0">
                            @foreach($categories as $category)
                                <li><button type="button">{{ $category['qc_name'] }}</button></li>
                            @endforeach
                            <li>
                                <input type="text" placeholder="新しいサブカテゴリー名を入力" name="category_name" class="new-text" value="{{ old('category_name') }}">
                            </li>
                                @error('category_name')
                                    <span class="error_text">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </ul>
                        <div class="btn mtb">
                            <button type="submit" class="add">追加する</button>
                        </div>
                        <button type="reset" class="cancel2" onclick="location.href='{{ route('admin.freq.sub_category.index', ['category'=>$category_id]) }}'">キャンセル</button>
                    </div>
                </div>
            </section>

            {{--<section>
                <div class="mt-10">
                    <button id="btn_back" class="btn btn-back wp-100" onclick="location.href='{{ route('admin.freq.sub_category.index') }}'" name="btn_back">戻る</button>
                </div>
            </section>--}}

        </div><!-- /tabs -->

        {{ Form::close() }}

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
            $(".menu_fraud").click(function (e) {
                e.preventDefault();
                /*$("#overlay").fadeToggle('slow');
                $(".hamburger").toggleClass("menu-close");*/
                $('.main-menu').addClass('hide');
                $('.second-menu-1').removeClass('hide');
            });
            $(".new-text").on("input", function() {
                var input = $(this).val(); //input に入力された文字を取得
                if(input){ //もし文字が入っていれば
                    $(".hidden").show();
                }else{
                    $(".hidden").hide();
                }
            });
        });

    </script>
@endsection

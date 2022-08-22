@extends('admin.layouts.app')
@section('title', 'カテゴリー管理')

@section('content')
    <div id="contents">

        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.index')])

        <div class="tabs form_page">
            <section>

                @include('admin.layouts.flash-message')

                <div id="main-contents">
                        <div class="search-result-area">
                            <table class="total">
                                <tr>
                                    <td class="text-left pb-0"><button type="button" class="new half" onclick="location.href='{{ route('admin.freq.category.new') }}'">新規追加</button></td>
                                    <td class="pb-0"><button type="button" class="edit half" onclick="location.href='{{ route('admin.freq.category.sort_category') }}'">並び替え</button></td>
                                </tr>
                                <tr>
                                    <td class="text-left"><button type="button" class="delete2 half" onclick="location.href='{{ route('admin.freq.category.delete') }}'">削除</button></td>
                                    <td><button type="button" class="update half" onclick="location.href='{{ route('admin.freq.category.public_category') }}'">更新</button></td>
                                </tr>
                            </table>
                            <h2>カテゴリー</h2>
                            <ul class="btn-list mt0">
                                @foreach($categories as $category)
                                    <li><a href="{{ route('admin.freq.sub_category.index', ['category'=>$category['qc_id']]) }}">{{ $category['qc_name'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

            </section>

            {{--<section>
                <div class="mt-10">
                    <button id="btn_back" class="btn btn-back wp-100" onclick="location.href='{{ route('admin.freq.index') }}'" name="btn_back">戻る</button>
                </div>
            </section>--}}

        </div><!-- /tabs -->
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
            /*$(".hamburger").click(function () {
                $("#overlay").fadeToggle('slow');
                $(".hamburger").toggleClass("menu-close");

            });
            $(".menu_fraud").click(function (e) {
                e.preventDefault();
                /!*$("#overlay").fadeToggle('slow');
                $(".hamburger").toggleClass("menu-close");*!/
                $('.main-menu').addClass('hide');
                $('.second-menu-1').removeClass('hide');
            });*/
        });

        /*$(function(){
            $('.sort').css('display', 'none');
            $(".extraction").change(function() {
                var extraction_val = $(".extraction").val();
                if(extraction_val == "すべて") {
                    $('.sort').css('display', 'none');
                }else if(extraction_val == "先輩向け") {
                    $('.sort').css('display', 'block');
                }else if(extraction_val == "後輩向け") {
                    $('.sort').css('display', 'block');
                }else if(extraction_val == "センパイについて") {
                    $('.sort').css('display', 'block');
                }else if(extraction_val == "アカウントについて") {
                    $('.sort').css('display', 'block');
                }else if(extraction_val == "各種機能・その他") {
                    $('.sort').css('display', 'block');
                }
            });
        });*/
    </script>
@endsection

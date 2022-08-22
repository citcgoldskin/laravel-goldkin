@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">問い合わせ</label>

            <section class="text-here">
                <div class="inner_box">
                    <h3>ユーザー種別</h3>
                    <p class="pd-20">センパイ</p>
                </div>
                <div class="inner_box">
                    <h3>お困りの内容</h3>
                    <p class="pd-20">操作方法がわからない</p>
                    <div class="input-text2 for-warning">
                        <textarea placeholder="" cols="50" rows="10" maxlength="1000" class="shadow-glay count-text" name="new_question" id="new_question"></textarea>
                    </div>
                </div>
            </section>

            <section class="text-here">
                <div class="inner_box">
                    <h3>返信</h3>
                    <div class="input-text2 for-warning">
                        <textarea placeholder="" cols="50" rows="10" maxlength="1000" class="shadow-glay count-text" name="new_question" id="new_question"></textarea>
                    </div>
                </div>
            </section>

            <div id="footer_button_area" class="under_area">
                <ul>
                    <li class="send-request">
                        <div class="btn_base btn_orange shadow">
                            <button type="button" class="modal-syncer" data-target="btn_cancel_reserve">返信内容を確認</button>
                        </div>
                    </li>
                </ul>
            </div>

        </div><!-- /tabs -->

        {{ Form::close() }}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .tab_item {
            border-bottom: 2px solid #f1f1f1 !important;
            border-top: 2px solid #dad8d6 !important;
        }
        section {
            padding-top: 10px !important;
            padding-bottom: 0px !important;
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
        .lb-period {
            width: 80px;
            font-size: 14px;
        }
        .select-period {
            width: calc(100% - 80px);
        }
        .todo_list {
            margin-bottom: 0px;
        }
    </style>
@endsection
@section('page_js')
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/i18n/datepicker-ja.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
            });

            // 検索条件
            $('.type_arrow').click(function(){
                if($(this).hasClass('opened')) {
                    $(this).removeClass('opened');
                    $(this).removeClass('type_arrow_top');
                    $(this).addClass('closed');
                    $(this).addClass('type_arrow_bottom');
                    $('.search-condition').addClass("hide");
                } else {
                    $(this).addClass('opened');
                    $(this).addClass('type_arrow_top');
                    $(this).removeClass('closed');
                    $(this).removeClass('type_arrow_bottom');
                    $('.search-condition').removeClass("hide");
                }
            });

            // profile
            $('.profile').click(function() {
                location.href = "{{ route('admin.fraud_report.detail', ['user'=>1]) }}";
            });

            $('#chk-purchase').change(function() {
                if ($('#purchase_period').hasClass('action-none')) {
                    $('#purchase_period').removeClass('action-none')
                } else {
                    $('#purchase_period').addClass('action-none')
                }
            });

            $('#chk-sales').change(function() {
                if ($('#sales_period').hasClass('action-none')) {
                    $('#sales_period').removeClass('action-none')
                } else {
                    $('#sales_period').addClass('action-none')
                }
            });
        });
    </script>
@endsection

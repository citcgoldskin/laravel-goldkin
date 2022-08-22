@extends('admin.layouts.app')

@section('title', config('const.text_contents.' . $type))

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <div class="under-title">
                <button type="button" onclick="location.href='{{ route('admin.master.index') }}'">＜</button>
                <label class="page-title" style="width: 100%;">{{ config('const.text_contents.' . $type) }}</label>
            </div>
            <section>
                {{ Form::open(["route"=> ["admin.master.save_text",'type'=>$type], "method"=>"post", "name"=>"frm_text", "id"=>"frm_text"]) }}

                <div id="main-contents">
                    <div class="search-result-area">

                        <input type="hidden" name="id" value="{{ is_object($main_visual) ? $main_visual->id : '' }}">

                        <h3>■本文</h3>
                        <textarea name="description" placeholder="説明文入力">{{ is_object($main_visual) ? $main_visual->description : '' }}</textarea>
                        <div class="btn mtb">
                            <button type="submit" id="btn_save">設定完了</button>
                        </div>
                    </div>
                </div>

                {{ Form::close() }}
            </section>
        </div>
    </div>
@endsection

@section('page_css')
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

        button.file-upload {
            font-size: 11px;
            padding: 10px 20px;
        }
    </style>

@endsection

@section('page_js')
    <script src="{{ asset('assets/vendor/ajaxupload/jquery.ajaxupload.js') }}"></script>
@endsection

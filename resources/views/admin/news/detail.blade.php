@extends('admin.layouts.app')

@section('title', 'ニュース内容')

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <div class="under-title">
                <button type="button" onclick="location.href='{{ route('admin.news.news_list') }}'">＜</button>
                <label class="page-title" style="width: 100%;">ニュース内容</label>
            </div>
            <section>
                <form method="GET" action="" accept-charset="UTF-8" name="form1" id="form1">
                    <div id="main-contents">
                        <div class="search-result-area">
                            <table class="total td-left">
                                <tbody><tr>
                                    <th>日時</th>
                                    {{--<td>{{ Carbon::parse($record->publish_time)->format('Y年m月d日 H:i') }}</td>--}}
                                    <td>{{ Carbon::parse($record->created_at)->format('Y年m月d日 H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>カテゴリー</th>
                                    <td><span>{{ config('const.news_category.' . $record->category) }}</span></td>
                                </tr>
                                <tr>
                                    <th>表題</th>
                                    <td>{{ $record->title }}</td>
                                </tr>
                                </tbody></table>
                            <table class="total td-left">
                                <tbody><tr class="block">
                                    <th>本文</th>
                                    <td><p>{{ $record->content }}</p></td>
                                </tr>
                                </tbody></table>
                            <div class="btn mtb">
                                <button type="button" onclick="location.href='{{ route('admin.news.edit', ['news'=>$record->id]) }}'">差し替えを行う</button>
                            </div>
                            <div class="btn mtb">
                                @if($record->status == config('const.news_status_code.publish') || $record->status == config('const.news_status_code.limit_publish') )
                                    <button type="button" onclick="location.href='{{ route('admin.news.to_private', ['news'=>$record->id]) }}'">非公開にする</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
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

        });

        function modalConfirm(modal_id="") {
            if(modal_id == "modal-caution") {
                $('#frm_destroy_maintenance').submit();
            }
        }
    </script>
@endsection

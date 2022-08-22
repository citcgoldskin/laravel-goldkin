@extends('admin.layouts.app')

@section('title', '下書き・非公開')

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <div class="under-title">
                <button type="button" onclick="history.back()">＜</button>
                <label class="page-title" style="width: 100%;">下書き・非公開</label>
            </div>
            <section>
                <form method="GET" action="" accept-charset="UTF-8" name="form1" id="form1">
                    <div id="main-contents">
                        <div class="search-result-area">
                            {{ Form::open(["route"=> ["admin.news.delete"], "method"=>"post", "name"=>"frm_news", "id"=>"frm_news"]) }}

                            <table class="total">
                                <tbody><tr>
                                    <th>全<em>{{ $records->count() }}</em>件</th>
                                    <td><button type="button" class="delete">削除する</button></td>
                                </tr>
                                </tbody></table>

                            <ul class="faq-list">
                                @foreach($records as $record)
                                    <li>
                                        <div class="check" style="display: none;">
                                            <input type="checkbox" name="ids[]" id="c{{ $record->id }}"><label for="c{{ $record->id }}"></label>
                                        </div>
                                        <div class="text">
                                            <a href="edit.php">
                                                <div class="flag-draft">
                                                    @switch($record->status)
                                                        @case(config('const.news_status_code.draft'))
                                                            <p>下書き</p>
                                                            @break
                                                        @case(config('const.news_status_code.n_publish'))
                                                        @case(config('const.news_status_code.limit_n_publish'))
                                                            <p>非公開</p>
                                                            @break
                                                    @endswitch
                                                </div>
                                                <h3>{{ $record->title }}</h3>
                                                <time>{{ Carbon::parse($record->created_at)->format('Y年m月d日 H:i') }}</time>
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            {{ $records->links('vendor.pagination.senpai-admin-pagination') }}
                            <div class="btn mtb checked-delete" style="display: none;">
                                <button type="submit">削除する</button>
                            </div>
                            {{ Form::close() }}
                        </div>

                    </div></form></section>
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
            $('.check').hide();
            $('.checked-delete').hide();
            $('.delete').click(function () {
                $('.checked-delete').toggle();
                $('.check').toggle();
                $('ul.faq-list li .text').toggleClass("no-click");
                if ($(this).text() === '削除する') {
                    $(this).text('完了');
                } else {
                    $(this).text('削除する');
                }
            });
        });
    </script>
@endsection

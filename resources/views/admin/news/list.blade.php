@extends('admin.layouts.app')

@section('title', 'ニュース一覧')

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <div class="under-title">
                <button type="button" onclick="location.href='{{ route('admin.news.index') }}'">＜</button>
                <label class="page-title" style="width: 100%;">ニュース一覧</label>
            </div>
            <section>
                    <div id="main-contents">
                        @include('admin.layouts.flash-message')
                        <div class="search-result-area">
                            <table class="total">
                                <tbody><tr>
                                    <th>全<em>{{ $records->count() }}</em>件</th>
                                    <td><div class="select-box w-short">
                                            <select class="extraction" id="sort">
                                                <option value="new" {{ $sort == 'new' ? 'selected' : '' }}>新しい順</option>
                                                <option value="old" {{ $sort == 'old' ? 'selected' : '' }}>古い順</option>
                                            </select>
                                        </div></td>
                                </tr>
                                </tbody></table>
                            <div class="sort" style="display: none;">
                                <table class="total">
                                    <tbody><tr>
                                        <th>絞り込み</th>
                                        <td><div class="select-box w-short">
                                                <select>
                                                    <option>すべて</option>
                                                </select>
                                            </div></td>
                                    </tr>
                                    </tbody></table>
                            </div>

                            {{ Form::open(["route"=> ["admin.news.news_list"], "method"=>"get", "name"=>"frm_list", "id"=>"frm_list"]) }}
                            <div class="search-box">
                                <input type="search" name="search_params[keyword]" id="keyword" placeholder="キーワードを入力" value="{{ isset($search_params['keyword']) ? $search_params['keyword'] : '' }}">
                                <input type="hidden" name="sort" value="{{ $sort }}">
                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(102, 102, 102, 1);transform: ;msFilter:;"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path></svg></button>
                            </div>
                            {{ Form::close() }}

                            <ul class="news-list">
                                @foreach($records as $record)
                                <li>
                                    <a href="{{ route('admin.news.detail', ['news'=>$record->id]) }}">
                                        <h3>{{ $record->title }}</h3>
                                        {{--<time>{{ Carbon::parse($record->publish_time)->format('Y年m月d日') }}</time>--}}
                                        <time>{{ Carbon::parse($record->created_at)->format('Y年m月d日') }}</time>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            {{ $records->links('vendor.pagination.senpai-admin-pagination') }}
                        </div>
                    </div>
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
            $('#sort').on('change', function() {
                location.href = "{{ route('admin.news.news_list') }}?sort=" + $(this).val() + "&search_params[keyword]=" + $('#keyword').val() ;
            });
        });
    </script>
@endsection

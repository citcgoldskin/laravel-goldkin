@extends('admin.layouts.app')

@section('title', 'メンテナンス管理')

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">メンテナンス管理</label>

            <section>

                @include('admin.layouts.flash-message')

                <div id="main-contents">
                        <div class="search-result-area">
                            <div class="btn mtb">
                                <button type="button" onclick="location.href='{{ route('admin.maintenance.create') }}'" class="new2">新規メンテナンス設定</button>
                            </div>

                            <h2>メンテナンス予定一覧</h2>

                            <ul class="out-money-list">
                                @if($maintenances->count() > 0)
                                    @foreach($maintenances as $maintenance)
                                        <li> <a href="{{ route('admin.maintenance.detail', ['maintenance'=>$maintenance->id]) }}">
                                            <div class="date">
                                                <h3>{{ Carbon::parse($maintenance->start_time)->format('Y年m月d日 H:i') }}<br>〜{{ Carbon::parse($maintenance->end_time)->format('Y年m月d日 H:i') }}</h3>
                                                <div class="alert"><p>{{ Carbon::parse($maintenance->notice_time)->format('Y年m月d日 H:i') }}</p></div>
                                            </div>
                                        </a> </li>
                                    @endforeach
                                @else
                                    <li>登録されたメンテナンスがありません。</li>
                                @endif
                            </ul>
                            {{ $maintenances->links('vendor.pagination.senpai-admin-pagination') }}
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

        });
    </script>
@endsection

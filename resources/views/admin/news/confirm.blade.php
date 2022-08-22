@extends('admin.layouts.app')

@section('title', 'メンテナンス設定確認')

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <div class="under-title">
                @isset($maintenance['id'])
                    <button type="button" onclick="location.href='{{ route('admin.maintenance.edit', ['maintenance'=>$maintenance['id']]) }}'">＜</button>
                @else
                    <button type="button" onclick="location.href='{{ route('admin.maintenance.create') }}'">＜</button>
                @endif
                <label class="page-title" style="width: 100%;">設定確認</label>
            </div>
            <section>
                @isset($maintenance['id'])
                    {{ Form::open(["route"=> ["admin.maintenance.update", ['maintenance'=>$maintenance['id']]], "method"=>"post", "name"=>"frm_maintenance", "id"=>"frm_maintenance"]) }}
                @else
                    {{ Form::open(["route"=> ["admin.maintenance.store"], "method"=>"post", "name"=>"frm_maintenance", "id"=>"frm_maintenance"]) }}
                @endif
                <div id="main-contents">
                    <div class="search-result-area">
                        <h2>メンテナンス予定日時</h2>
                        <h3>{{ Carbon::parse($maintenance['start_time'])->format('Y年m月d日 H:i') }}<br>〜{{ Carbon::parse($maintenance['end_time'])->format('Y年m月d日 H:i') }}</h3>
                        <div class="bb pb10"></div>
                        <h2>対象サービス</h2>
                        <ul class="list">
                            @foreach(config('const.maintenance_services') as $key => $name)
                                @if(in_array($key, $maintenance['services']))
                                    <li>・{{ $name }}</li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="bb pb10"></div>
                        <h2>通知日時</h2>
                        <p>{{ Carbon::parse($maintenance['notice_time'])->format('Y年m月d日 H:i') }}</p>

                        <div class="btn mtb">
                            <button type="submit" class="send">確定する</button>
                        </div>
                        <div class="btn mtb">
                            @isset($maintenance['id'])
                                <button type="button" class="del" onclick="location.href='{{ route('admin.maintenance.edit', ['maintenance'=>$maintenance['id']]) }}'">戻る</button>
                            @else
                                <button type="button" class="del" onclick="location.href='{{ route('admin.maintenance.create') }}'">戻る</button>
                            @endif
                        </div>
                    </div>
                    {{ Form::close() }}
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

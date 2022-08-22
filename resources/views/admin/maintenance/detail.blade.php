@extends('admin.layouts.app')

@section('title', 'メンテナンス予定')

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <div class="under-title">
                <button type="button" onclick="location.href='{{ route('admin.maintenance.index') }}'">＜</button>
                <label class="page-title" style="width: 100%;">メンテナンス予定</label>
            </div>
            <section>
                <div id="main-contents">
                    <div class="search-result-area">
                        <h2>メンテナンス予定日時</h2>
                        <h3>{{ Carbon::parse($maintenance->start_time)->format('Y年m月d日 H:i') }}<br>〜{{ Carbon::parse($maintenance->end_time)->format('Y年m月d日 H:i') }}</h3>

                        <div class="bb pb10"></div>
                        <h2>対象サービス</h2>
                        <ul class="list">
                            @foreach(config('const.maintenance_services') as $key => $name)
                                @if(in_array($key, $maintenance->arr_services))
                                    <li>・{{ $name }}</li>
                                @endif
                            @endforeach
                        </ul>

                        <div class="bb pb10"></div>
                        <h2>通知日時</h2>
                        <p>{{ Carbon::parse($maintenance->notice_time)->format('Y年m月d日 H:i') }}</p>

                        <div class="btn mtb">
                            <button type="button" onclick="location.href='{{ route('admin.maintenance.edit', ['maintenance' => $maintenance->id]) }}'">この予定を変更する</button>
                        </div>
                        <div class="btn mtb">
                            <button type="button" class="del modal-syncer" data-target="modal-caution">この予定を削除する</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- /tabs -->
    </div>
    {{ Form::open(["route"=> ["admin.maintenance.delete", ['maintenance'=>$maintenance->id]], "method"=>"post", "name"=>"frm_destroy_maintenance", "id"=>"frm_destroy_maintenance"]) }}
    {{ Form::close() }}

    @include('admin.layouts.modal-layout', [
            'modal_id'=>"modal-caution",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"メンテナンスを削除してもよろしいですか？",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"キャンセル",
        ])
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

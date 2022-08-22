@extends('admin.layouts.app')

@section('title', config('const.main_visuals.' . $type))

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <div class="under-title">
                <button type="button" onclick="location.href='{{ route('admin.master.index') }}'">＜</button>
                <label class="page-title" style="width: 100%;">{{ config('const.main_visuals.' . $type) }}</label>
            </div>
            <section>
                {{ Form::open(["route"=> ["admin.master.save_guid",'type'=>$type], "method"=>"post", "name"=>"frm_guid", "id"=>"frm_guid"]) }}
                @php
                    $main_visual = $main_visuals[0];
                @endphp
                <div id="main-contents">
                    <div class="search-result-area">
                        <h2>{{ $page }}枚目</h2>

                        <input type="hidden" name="page" value="{{ $page }}">
                        <input type="hidden" name="id" value="{{ is_object($main_visual) ? $main_visual->id : '' }}">

                        <h3>■説明</h3>
                        <textarea name="description" placeholder="説明文入力">{{ is_object($main_visual) ? $main_visual->description : '' }}</textarea>

                        <h3 class="mt-20">■画像</h3>
                        <div class="xzk k2">
                            @php
                                $file_path = old('file_path', is_object($main_visual) ? $main_visual->file_path : '');
                                $image_url = '';
                                if(is_object($main_visual)) {
                                     if(Storage::disk('main_visual')->exists($type.'/'.$file_path)) {
                                        $image_url = asset("storage/main_visual/{$type}/" . $file_path);
                                    }
                                }
                                if(Storage::disk('temp')->exists($file_path)) {
                                    $image_url = asset("storage/temp/" . $file_path);
                                }
                            @endphp

                            <button type="button" class="new upload-pic a-upload file-upload" data-file_path="{{ old('file_path', is_object($main_visual) ?  $main_visual->file_path : '') }}">+ファイル選択</button>

                            <img id="visual_img" class="{{ $image_url? '' : 'hide' }}" src="{{ $image_url ? $image_url :  '' }}">

                            <div class="upload-progress hide" id="fileprogressbar_lesson_image" style=""></div>

                            <button type="button" class="delete2 a-del-center btn-delete-file {{  old('file_path', is_object($main_visual) ?  $main_visual->file_path : '') ? '' : 'hide' }}" id="delete_image" file_path="{{ old('file_path', is_object($main_visual) ?  $main_visual->file_path : '') }}">ⅹ削除</button>

                            <input type="hidden" name="file_path" id="file_path" value="{{ old('file_path', is_object($main_visual) ?  $main_visual->file_path : '') }}" />
                        </div>

                        @error('file_path')
                        <span  class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <h3 class="mt-20">■画像クリック後の参照先</h3>
                        <input type="text" name="link" value="{{ is_object($main_visual) ? $main_visual->link : '' }}">

                        <div class="btn mtb">
                            <button type="submit" id="btn_save">設定完了</button>
                        </div>
                        @if(is_object($main_visual) )
                            <div class="btn mtb">
                                <button type="button" onclick="location.href='{{ route('admin.master.main_visual', ['type'=>$type, 'page'=>$page+1]) }}'">次枚目の作成</button>
                            </div>
                            <div class="btn mtb">
                                <button type="reset" id="btn_remove_page" class="modal-syncer" data-target="modal-remove-page">この枚目を削除</button>
                            </div>
                        @endif
                        <div class="btn mtb">
                            <button type="reset" id="btn_clear_all" class="modal-syncer" data-target="modal-clear-type">設定内容をクリアする</button>
                        </div>
                    </div>
                    {{ $main_visuals->links('vendor.pagination.senpai-admin-pagination') }}
                </div>

                {{ Form::close() }}
            </section>
        </div>
    </div>

    @if(is_object($main_visual) )
        {{ Form::open(["route"=> ["admin.master.remove_visual_page",['main_visual'=>$main_visual->id, 'page'=>$page]], "method"=>"post", "name"=>"frm_remove_page", "id"=>"frm_remove_page"]) }}
        {{ Form::close() }}
    @endif

    @if($main_visuals->count() > 0)
        {{ Form::open(["route"=> ["admin.master.clear_visual",'type'=>$type], "method"=>"post", "name"=>"frm_clear_visual", "id"=>"frm_clear_visual"]) }}
        {{ Form::close() }}
    @endif

    @include('admin.layouts.modal-layout', [
    'modal_id'=>"modal-remove-page",
    'modal_type'=>config('const.modal_type.confirm'),
    'modal_title'=>"この枚目を削除してもよろしいですか？",
    'modal_confrim_btn'=>"OK",
    'modal_confrim_cancel'=>"キャンセル",
])

    @include('admin.layouts.modal-layout', [
        'modal_id'=>"modal-clear-type",
        'modal_type'=>config('const.modal_type.confirm'),
        'modal_title'=>"設定内容をクリアしてもよろしいですか？",
        'modal_confrim_btn'=>"OK",
        'modal_confrim_cancel'=>"キャンセル",
    ])
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
    <script>
        let file_path;
        $(document).ready(function() {
            $('.file-upload').click(function(e){
                file_path = $(this).attr('file_path');
                $.ajaxUploadSettings.name = 'vfile';
            }).ajaxUploadPrompt({
                url : '{{ route("admin.master.upload_file") }}',
                data: {
                    _token:'{{ csrf_token() }}'
                },
                beforeSend : function (xhr, opts) {
                    fullPath = $("input[name=vfile]").val();
                    if (fullPath) {
                        let startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                        let filename = fullPath.substring(startIndex);
                        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                            filename = filename.substring(1);
                        }
                        g_filename = filename;
                    }
                },
                onprogress : function (e) {
                    let obj_str = "fileprogressbar_lesson_image";
                    if (e.lengthComputable) {
                        let percentComplete = e.loaded / e.total;
                        $( "#"+obj_str).removeClass('hide');
                        $( "#"+obj_str).progressbar({
                            value: percentComplete*100,
                            change: function(e, ui) {
                            },
                            complete: function () {
                                $(this).progressbar( "destroy" );
                                $( "#"+obj_str).addClass('hide');
                            }
                        });
                    }
                },
                error : function () {
                    alert("ファイルアップロードに失敗しました。");
                },
                success : function (results) {
                    $("#file_path").val(results['path']);

                    $("#delete_image").attr('file_path', results['path']);
                    $("#delete_image").removeClass('hide');

                    $("#file_link_image").text(results['name']);
                    $("#file_link_image").attr('href', '{{ asset("storage/temp") }}/'+results['path']);
                    $("#file_link_image").removeClass('hide');
                    $("#visual_img").removeClass('hide');
                    $("#visual_img").attr('src', '{{ asset("storage/temp") }}/'+results['path']);

                },
                accept: "image/*"
            });

            $("#delete_image").click(function() {
                file_path = $(this).attr('file_path');
                let confirmed = confirm('画像を削除します。よろしいですか？');
                if(confirmed){
                    $("#file_path").val('');

                    $("#delete_image").attr('file_path', '');
                    $("#delete_image").addClass('hide');

                    $("#file_link_image").text('');
                    $("#file_link_image").attr('href', '');
                    $("#file_link_image").addClass('hide');
                    $("#visual_img").attr('src', '');
                    $("#visual_img").addClass('hide');
                }
            });
        });
        function previewImage(obj)
        {
            var fileReader = new FileReader();
            fileReader.onload = (function() {
                document.getElementById('preview').src = fileReader.result;
            });
            fileReader.readAsDataURL(obj.files[0]);
        }

        function modalConfirm(modal_id="") {
            if(modal_id == "modal-remove-page") {
                $('#frm_remove_page').submit();
            }

            if(modal_id == "modal-clear-type") {
                $('#frm_clear_visual').submit();
            }
        }
    </script>
@endsection

@extends('admin.layouts.app')
@section('content')
    <div class="modal">
        <div id="completion_wrap" class="no_modal">
            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        {!! isset($contents) ? $contents : '' !!}
                    </h2>
                </div>
            </div>
            <div class="button-area">
                <div class="btn_base btn_white shadow">
                    @if(isset($modal_confrim_url) && $modal_confrim_url)
                        <a href="{{ $modal_confrim_url }}">{{ isset($ok_label) && $ok_label ? $ok_label : 'OK' }}</a>
                    @endif
                </div>
                @if(isset($modal_cancel_url) && $modal_cancel_url)
                    <div class="btn_base btn_white shadow">
                        <a href="{{ $modal_cancel_url }}">{{ isset($cancel_label) && $cancel_label ? $cancel_label : 'キャンセル' }}</a>
                    </div>
                @endif
            </div>
        </div>
        <!--main_visual A-21 end-->
    </div>
@endsection



@if (isset($modal_type) && $modal_type == config('const.modal_type.confirm'))
    <div class="modal-wrap">
        <div id="{{ isset($modal_id) && $modal_id ? $modal_id : '' }}" class="modal-content">
            <div class="modal_body completion">
                @if(isset($modal_title) && $modal_title)
                    <div class="modal_inner">
                        <h2 class="modal_ttl">
                            {!! $modal_title !!}
                        </h2>
                        @if(isset($modal_content_area))
                            <div id="{{ $modal_content_area ? $modal_content_area : '' }}" class="modal_content_area mt-20 al-c fs-14">

                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="button-area">
                @if(isset($modal_confrim_btn))
                    <div class="btn_base btn_orange">
                        <button type="button" onclick="modalConfirm('{{ $modal_id }}');">{{ isset($modal_confrim_btn) && $modal_confrim_btn ? $modal_confrim_btn : 'OK' }}</button>
                    </div>
                @endif
                @if(isset($modal_confrim_cancel))
                    <div class="btn_base btn_gray-line">
                        <a id="modal-close" class="button-link">{{ isset($modal_confrim_cancel) && $modal_confrim_cancel ? $modal_confrim_cancel : '戻る' }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

@if (isset($modal_type) && $modal_type == config('const.modal_type.alert'))
    <div class="modal-wrap">
        <div id="{{ isset($modal_id) && $modal_id ? $modal_id : '' }}" class="modal-content">
            <div class="modal_body completion">
                @if(isset($modal_title) && $modal_title)
                    <div class="modal_inner">
                        <h2 class="modal_ttl">
                            {{ $modal_title }}
                        </h2>
                    </div>
                @endif
            </div>

            <div class="button-area">
                @if(isset($modal_confrim_btn))
                    <div class="btn_base btn_ok">
                        @if(isset($modal_confrim_url) && $modal_confrim_url)
                            <a id="modal-close" href="{{ $modal_confrim_url }}" class="button-link">{{ isset($modal_confrim_btn) && $modal_confrim_btn ? $modal_confrim_btn : 'OK' }}</a>
                        @else
                            <a id="modal-close" class="button-link">{{ isset($modal_confrim_btn) && $modal_confrim_btn ? $modal_confrim_btn : 'OK' }}</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif




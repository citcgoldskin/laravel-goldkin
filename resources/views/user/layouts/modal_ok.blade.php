@extends('user.layouts.app')
@section('content')
<div class="modal">
    <!--main_visual A-21-->
    <div id="completion_wrap" class="no_modal">
        <div class="modal_body completion">
            <div class="modal_inner">
                <h2 class="modal_ttl">
                    <?php echo isset($contents) ? $contents : ""; ?>
                </h2>
            </div>
        </div>
        <div class="button-area">
            <div class="btn_base btn_white shadow">
                @if(isset($url) && $url)
                    @if($url == "keijibann")
                        <a href="{{route('keijibann.list')}}">OK</a>
                    @else
                        <a href="{{$url}}">OK</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <!--main_visual A-21 end-->
</div>
@endsection


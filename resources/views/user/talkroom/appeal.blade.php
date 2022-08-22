@extends('user.layouts.app')
<style>
    header {
        background: none;
    }
</style>

<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')

    @include('user.layouts.header_info')


    <div id="contents">

    <!--main_-->
    <form name="form1" id="form1">

        <section>

            <div class="inner_box">
                <h3 class="must">通報する理由を以下から選んでください</h3>
                <div class="white_box">
                    <div class="check-box">
                        @foreach( $appClass as $k => $v )
                        <div class="clex-box_02">
                            @if($v['name']=="その他")
                                {{--<input type="checkbox" name="report" id="{{$v['id']}}" onclick="showBalloon()">--}}
                                <input type="checkbox" name="report" id="{{$v['id']}}" data-category="{{ $v['name'] }}">
                            @else
                                <input type="checkbox" name="report" id="{{$v['id']}}">
                            @endif
                            <label for="{{$v['id']}}">
                                <p
                                    @php
                                      if ($v['name']=="その他")
                                         echo 'class="click-balloon hei-auto"';
                                    @endphp
                                 >{{$v['name']}}</p>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="" id="makeImg">
                <div class="balloon balloon_white">
                    <textarea placeholder="理由を200字以内でご記入ください。" cols="50" rows="10" maxlength="200" id="note"></textarea>
                </div>
            </div>


        </section>

    </form>

    <div class="form_txt txt_center pb30">
        <p><strong>通報すると当該ユーザーの情報を送信します。</strong></p>
    </div>

    <div class="button-area">
        <div class="btn_base btn_orange shadow">
            <button class="ajax-modal-syncer"  data-target="#modal-report" id="appealSendBtn">同意して送信</button>
            <button class="ajax-modal-syncer hide"  data-target="#modal-warning" id="appealWarningBtn">同意して送信</button>
        </div>
    </div>

</div><!-- /contents -->

<!-- モーダル部分 *********************************************************** -->
<iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
<div class="modal-wrap ok">
    <div id="modal-report" class="modal-content ok">

        <div class="modal_body completion">
            <div class="modal_inner">
                <h2 class="modal_ttl">
                    〇〇〇〇さんを<br>
                    通報しました
                </h2>
            </div>
        </div>

        <div class="button-area">
            <div class="btn_base btn_ok">
                <a href="{{ route("user.talkroom.list", ['type'=>$type]) }}" class="button-link">OK</a>
            </div>
        </div>
    </div>
</div>

<div class="modal-wrap ok">
    <div id="modal-warning" class="modal-content ok">

        <div class="modal_body completion">
            <div class="modal_inner">
                <h2 class="modal_warning_ttl">
                    〇〇〇〇さんを<br>
                    通報しました
                </h2>
            </div>
        </div>

        <div class="button-area">
            <div class="btn_base btn_ok">
                <a id="modal-close" class="button-link">OK</a>
            </div>
        </div>
    </div>
</div>
<div id="modal-overlay2" style="display: none;"></div>
    @include('user.layouts.modal')
@endsection

<!-- ********************************************************* -->
@section('page_js')
<script>
    $("#appealSendBtn").on('click', function () {
         var appeals = $("input[name=report]");
        var vals = {};
        let count = 0;
        let other_checked = false; // その他
        for (var i = 0, l = appeals.length; l > i; i++) {
            vals[appeals[i].id] = appeals[i].checked;
            let category = getAttribute(appeals[i], 'data-category');
            if(category == "その他") {
                other_checked = true;
            }
            if(appeals[i].checked) {
                count ++;
            }
        }
        if (count == 0) {
            $(".modal_warning_ttl").html('通報する理由を選択してください。' );
            showAjaxModal($("#appealWarningBtn"));
            return;
        }

        var note = $("#note").val();
        if (note.trim() == "" && other_checked) {
            $(".modal_warning_ttl").html('理由詳細を入力してください。' );
            showAjaxModal($("#appealWarningBtn"));
            return;
        }

        $.ajax({
            type: "post",
            url: " {{ route('user.talkroom.sendAppeals') }}",
            data: {
                appealId: "{{ $fromUserId }}",
                _token: "{{ csrf_token() }}",
                vals: vals,
                note: note
            },
            dataType: "json",
            success: function(data) {
                if (data.state) {
                    $(".modal_ttl").html(data.name + 'さんを<br>通報しました' );
                }else{
                    $(".modal_ttl").html('通報送信が失敗しました' );//@todo need to confirm
                }
                showAjaxModal($("#appealSendBtn"));
            },
            error: function(){
                $(".modal_ttl").html('通報送信が失敗しました' );
                showAjaxModal($("#appealSendBtn"));
            },
            complete: function() {
            }
        });
    });

    function getAttribute(node, attr_name) {
        let result = "";
        $.each( node.attributes, function ( index, attribute ) {
            if (attr_name == attribute.name) {
                result = attribute.value
            }
        });
        return result;
    }
</script>
@endsection



@extends('user.layouts.app')

@section('title', 'エリアを選択')

@section('sub_title', '(複数可能)')

@section('$page_id', 'home')

@php
    use App\Service\CommonService;
@endphp

@section('content')

    @include('user.layouts.header_under')

    <!--***************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">

  <!--main_-->
@if(isset($page_from) && $page_from == "keijibann")
    {{ Form::open(['route' => ['keijibann.set_area'], "name"=>"form1", "id"=>"form1", "method"=>"post"]) }}
@else
    {{ Form::open(['route' => ['user.lesson.set_area'], "name"=>"form1", "id"=>"form1", "method"=>"post"]) }}
@endif
@php
	$city_count = count($data);
@endphp

<!-- 大阪市 ************************************************** -->
	<input type="hidden" name="area_count" value="{{$city_count}}" id="area_count">
	{{--@foreach($data as $key => $value)
	<section>
		<div class="white_box">
		  <div class="check_area">
				<div class="check-list">
					@php
						$area_name = $value['area_name'];
						$district_count = count($value['area']);
						$lesson_count = 0;
					@endphp
					@foreach($value['area'] as $k => $v)
						@php
							$lesson_count += $v['lesson_count'];
						@endphp
					@endforeach
					<div class="clex-box_02 bold">
						<input type="hidden" name="area_{{$key}}_0" id="area_{{$key}}_0"
                               @if($value['selected'])
                                    value="1"
                               @else
                                    value="0"
                                @endif
                        >
                        <input type="hidden" name="area_{{$key}}_name" value="{{$area_name}} すべて">
						<input type="hidden" name="area_{{$key}}_count" value="{{$district_count}}" id="area_{{$key}}_count">
						<input type="checkbox" name="province" id="c_{{$key}}_0"
							@if($value['selected'])
								checked="checked"
							@endif
						>
						<label for="c_{{$key}}_0" name="area_label" value="0" id="label_{{$key}}_0" >
							<p>{{$area_name}} すべて<span>（{{CommonService::showFormatNum($lesson_count)}}）</span></p>
						</label>
					</div>

					@foreach($value['area'] as $k => $v)
						<div class="clex-box_02">
							<input type="hidden" name="area_{{$key}}_{{$k + 1}}" id="area_{{$key}}_{{$k + 1}}"
                                   @if($value['selected'] || $v['selected'])
                                        value="1"
                                   @else
                                        value="0"
                                    @endif
                            >
							<input type="hidden" name="area_{{$key}}_{{$k + 1}}_id" value="{{$v['area_id']}}" id="area_{{$key}}_{{$k + 1}}_id">
                            <input type="hidden" name="area_{{$key}}_{{$k + 1}}_name" value="{{$area_name}} {{$v['area_name']}}" id="area_{{$key}}_{{$k + 1}}_name">
							<input type="checkbox" name="area" id="c_{{$key}}_{{$k + 1}}"
								@if($value['selected'] || $v['selected'])
									checked="checked"
								@endif
							>
							<label for="c_{{$key}}_{{$k + 1}}" name="area_label" value="0" id="label_{{$key}}_{{$k + 1}}" >
								<p>{{$area_name}} {{$v['area_name']}}<span>（{{CommonService::showFormatNum($v['lesson_count'])}}）</span></p>
							</label>
						</div>
					@endforeach
				</div>

		  </div>

		</div>

	  </section>
	@endforeach--}}

    <section>
        <div class="white_box">
            @foreach($data as $k => $v)
                <div class="check_area">
                    <div class="check-list">
                        <div class="clex-box_02">
                            <input type="hidden" name="area_{{$k + 1}}" id="area_{{$k + 1}}"
                                   @if($v['selected'] || $v['selected'])
                                   value="1"
                                   @else
                                   value="0"
                                @endif
                            >
                            <input type="hidden" name="area_{{$k + 1}}_id" value="{{$v['area_id']}}" id="area_{{$k + 1}}_id">
                            <input type="hidden" name="area_{{$k + 1}}_name" value="{{$v['area_name']}}" id="area_{{$k + 1}}_name">
                            <input type="checkbox" name="area" id="c_{{$k + 1}}"
                                   @if($v['selected'] || $v['selected'])
                                   checked="checked"
                                @endif
                            >
                            <label for="c_{{$k + 1}}" name="area_label" value="0" id="label_{{$k + 1}}" >
                                @if(isset($page_from) && $page_from == "keijibann")
                                    <p>{{$v['area_name']}}<span>（{{CommonService::showFormatNum($v['recruit_count'])}}）</span></p>
                                @else
                                    <p>{{$v['area_name']}}<span>（{{CommonService::showFormatNum($v['lesson_count'])}}）</span></p>
                                @endif
                            </label>
                        </div>
                    </div>

                </div>
            @endforeach
            </div>
    </section>

{{ Form::close()}}

</div><!-- /contents -->

  <footer>
	  <div id="footer_button_area" class="result">
	    <ul>
		 <li>
		  <div class="btn_base btn_white clear_btn">
		   <a onclick="clearAll()">クリア</a>
		  </div>
		 </li>
		 <li>
			 <div class="btn_base btn_white" id="OKbtn">
				 <button id="submit_btn">設定する</button>
			 </div>
		 </li>
		</ul>
	  </div>
   </footer>

@endsection

@section('page_js')
<script>

    $("#submit_btn").click(function() {
        $('#form1').submit();
    });

    $('input[name="province"]').change(function(){
        var i, j, value, child_count, id;
        var count = $("#area_count").val();
		id = this.id;
        for(i = 0; i < count; i++){
            if($("#c_" + i + "_0").prop('checked'))
                $("#area_" + i + "_0").val(1);
            else
                $("#area_" + i + "_0").val(0);
			if(id == "c_" + i + "_0"){
                child_count = $("#area_" + i + "_count").val();
                if(this.checked){
                    for(j = 1; j <= child_count; j++){
                        $("#c_" + i + "_" + j).prop('checked', true);
                        $("#area_" + i + "_" + j).val(1);
                    }
                }else{
                    for(j = 1; j <= child_count; j++){
                        $("#c_" + i + "_" + j).prop('checked', false);
                        $("#area_" + i + "_" + j).val(0);
                    }
                }
			}
		}
    });

    $('input[name="area"]').change(function(){
        /*var i, j, value, child_count;
        var count = $("#area_count").val();
        for(i = 0; i < count; i++){
            if($("#c_" + i + "_0").prop('checked'))
                $("#area_" + i + "_0").val(1);
            else
                $("#area_" + i + "_0").val(0);
            child_count = $("#area_" + i + "_count").val();
            for(j = 1; j <= child_count; j++){
                if($("#c_" + i + "_" + j).prop('checked'))
                    $("#area_" + i + "_" + j).val(1);
                else
                    $("#area_" + i + "_" + j).val(0);
            }
        }*/

        var i, j, value, child_count;
        var count = $("#area_count").val();
        for(j = 1; j <= count; j++){
            if($("#c_" + j).prop('checked'))
                $("#area_" + j).val(1);
            else
                $("#area_" + j).val(0);
        }
    });
	function clearAll(){
        /*var i, j, child_count;
        var count = $("#area_count").val();
        for(i = 0; i < count; i++){
            $("#c_" + i + "_0").prop('checked', false);
            $("#area_" + i + "_0").val(0);
            child_count = $("#area_" + i + "_count").val();
            for(j = 1; j <= child_count; j++){
                $("#c_" + i + "_" + j).prop('checked', false);
                $("#area_" + i + "_" + j).val(0);
            }
        }*/
        var i, j, child_count;
        var count = $("#area_count").val();
        for(j = 1; j <= count; j++){
            $("#c_" + j).prop('checked', false);
            $("#area_" + j).val(0);
        }
	}
</script>
@endsection

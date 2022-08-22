@extends('user.layouts.app')

@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->


<div id="contents">

    <!--main_-->
    <form action="{{$_SERVER['HTTP_REFERER']}}" method="get" name="form1" id="form1">
        <section>

            <div class="inner_box">
                <input type="hidden" value="{{$act_type_name}}" name="act_type_name">
                <input type="hidden" value="{{$act_id}}" name="act_id">
                <h3>主要金融機関</h3>
                <div class="white_box_02">
                    <div class="check-box">
                        @foreach($fav_bank_list as $k => $v)
                            <div @if($v['bnk_id'] == $bnk_id) class="clex-box_04 sub on" @else class="clex-box_04 sub" @endif>
                                <input type="submit" value="{{$v['bnk_name']}}" name="bnk_name">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            <div class="inner_box">
                <h3>主要金融機関50音順</h3>

                <!-- ************************************************************ -->
                @for($i = 0; $i < 35; $i += 5)
                    <div class="board_box set-list_wrap">
                        <input id="initial-{{$i}}" name="acd" class="acd-check" type="radio">
                        <label class="acd-label" for="initial-{{$i}}">{{$alphabet_arr[$i]}}行</label>
                        <div class="acd-content set-list_content">
                            <ul>
                                @for($j = $i; $j < $i + 5; $j++)
                                    <li><a href="{{ route('user.myaccount.sel_bank_alphabet', ['prev_url_id' => $prev_url_id, 'alpha_id' => $j, 'prefix' => $alphabet_arr[$i], 'act_type_name' => $act_type_name]) }}">{{$alphabet_arr[$j]}}</a></li>
                                @endfor
                            </ul>
                        </div>
                    </div>
                @endfor

                <!-- ************************************************************ -->
                <div class="board_box set-list_wrap">
                    <input id="initial-35" name="acd" class="acd-check" type="radio">
                    <label class="acd-label" for="initial-35">{{$alphabet_arr[35]}}行</label>
                    <div class="acd-content set-list_content">
                        <ul>
                            @for($j = 35; $j < 38; $j++)
                                <li><a href="{{ route('user.myaccount.sel_bank_alphabet', ['prev_url_id' => $prev_url_id, 'alpha_id' => $j, 'prefix' => $alphabet_arr[35], 'act_type_name' => $act_type_name]) }}">{{$alphabet_arr[$j]}}</a></li>
                            @endfor
                        </ul>
                    </div>
                </div>
                <!-- ************************************************************ -->
                <div class="board_box set-list_wrap">
                    <input id="initial-38" name="acd" class="acd-check" type="radio">
                    <label class="acd-label" for="initial-38">{{$alphabet_arr[38]}}行</label>
                    <div class="acd-content set-list_content">
                        <ul>
                            <li><a href="{{ route('user.myaccount.sel_bank_alphabet', ['prev_url_id' => $prev_url_id, 'alpha_id' => 38, 'prefix' => $alphabet_arr[38], 'act_type_name' => $act_type_name]) }}">{{$alphabet_arr[38]}}</a></li>
                        </ul>
                    </div>
                </div>

            </div>

        </section>
    </form>

</div><!-- /contents -->

@endsection

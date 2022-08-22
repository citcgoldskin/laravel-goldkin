@extends('user.layouts.app')

@section('title', 'センパイ出品')

@section('content')

    @if(isset($page_from))
        @include('user.layouts.header_under')
    @else
        @include('user.layouts.gnavi_under')
    @endif

    @php $sex = ['', '女性', '男性']; @endphp
    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">

          <section class="tab_area tab_white mb0">
            <div class="switch_tab three_tab">
                <div class="type_radio radio-01">
                <input type="radio" name="onof-line" id="off-line" onclick="location.href='{{route('user.syutupinn.lesson_list')}}'">
                <label class="ok" for="off-line">出品レッスン</label>
                </div>
                <div class="type_radio radio-02">
                <input type="radio" name="onof-line" id="on-line-1" onclick="location.href='{{route('user.syutupinn.schedule')}}'">
                <label class="ok" for="on-line-1">出勤カレンダー</label>
                </div>
                <div class="type_radio radio-03">
                <input type="radio" name="onof-line" id="on-line-2" checked="checked">
                <label class="ok" for="on-line-2">リクエスト</label>
                    @if($req_count != '')<span class="midoku">{{$req_count}}</span>@endif
                </div>
            </div>
          </section>

    <div class="tabs info_wrap mt0">
              <input id="tab-01" type="radio" name="tab_item"  {{$type == config('const.request_type.reserve') ? 'checked' : ''}}>
              <label class="tab_item" for="tab-01">
               予約リクエスト@if($count1 != '')<span class="midoku">{{$count1}}</span>@endif
              </label>
              <input id="tab-02" type="radio" name="tab_item"  {{$type == config('const.request_type.attend') ? 'checked' : ''}}>
              <label class="tab_item" for="tab-02">
               出勤リクエスト@if($count2 != '')<span class="midoku">{{$count2}}</span>@endif
              </label>


        <!-- ********************************************************* -->
         <div class="tab_content" id="tab-01_content">
              <section>
                @if(isset($req_list1) && count($req_list1) > 0)
                    @foreach($req_list1 as $k=>$v)
                          <div class="board_box">
                              <a href="{{route('user.syutupinn.reserve_check',['lr_id'=>$v['lr_id']])}}">
                                  <ul class="teacher_info_03 mt0">
                                      <li class="icon_s40"><img src="{{$v['kouhai_avatar']}}" alt="プロフィールアイコン"></li>
                                      <li class="about_teacher">
                                          <div class="profile_name">
                                              <p>{{$v['kouhai_name']}}<br><span>（{{ $v['kouhai_old']  }}）{{$sex[$v['kouhai_sex']]}}</span></p>
                                          </div>
                                      </li>
                                      <li><img src="{{$v['lesson_class_icon']}}" alt="カテゴリーアイコン"></li>
                                  </ul>

                                  <div>
                                      <p class="lesson_ttl">{{$v['lesson_title']}}</p>
                                      <p class="target_area">レッスン場所：{{$v['lesson_pos_discuss'] == 0 ? ($v['lesson']['lesson_area_names'] ? implode('/', $v['lesson']['lesson_area_names']) : '') : ($v['lesson_discuss_area'] ? $v['lesson_discuss_area'] : '')}}</p>
                                  </div>
                                  <div class="kigen_wrap">
                                      <h4>承認期限：{{ $v['until_confirm'] }}</h4>
                                      <ul class="list_area">
                                          @if(isset($v['req']) && count($v['req']) > 0)
                                              @foreach($v['req'] as $k1=>$v1)
                                                  @php $arr = explode('|',$v1); @endphp
                                                      <li>
                                                          <div>
                                                              {{$arr[0]}}&nbsp;&nbsp;&nbsp;{{$arr[1]}}
                                                          </div>
                                                          <div>{{$arr[2]}}円</div>
                                                      </li>
                                              @endforeach
                                          @endif
                                          @if(isset($v['confirm']) && count($v['confirm']) > 0)
                                              @foreach($v['confirm'] as $k1=>$v1)
                                                      <li class="expired">
                                                          <h5>承認期限：{{$k1}} </h5>
                                                  @foreach($v1 as $k2=>$v2)
                                                          @php $arr = explode('|',$v2); @endphp
                                                              <p>
                                                                    <span class="dete">
                                                                        {{$arr[1]}}&nbsp;&nbsp;&nbsp;{{$arr[2]}}
                                                                    </span>
                                                                  <span class="price">{{$arr[3]}}円</span>
                                                              </p>
                                                      @endforeach
                                                      </li>
                                              @endforeach
                                          @endif
                                      </ul>
                                  </div>
                              </a>
                          </div>
                    @endforeach
                {{--@else
                      <div class="no-data">検索結果 0件</div>--}}
                @endif
              </section>
         </div><!-- /tab_content -->


        <!-- ********************************************************* -->

         <div class="tab_content" id="tab-02_content">
          <section>
              @if(isset($req_list2) && count($req_list2) > 0)
                  @foreach($req_list2 as $k=>$v)
                        <div class="board_box">
                            <a href="{{route('user.syutupinn.attend_check', ['lr_id'=>$v['lr_id']])}}">
                                <ul class="teacher_info_03 mt0">
                                    <li class="icon_s40"><img src="{{$v['kouhai_avatar']}}" class="プロフィールアイコン"></li>
                                    <li class="about_teacher">
                                        <div class="profile_name">
                                            <p>{{$v['kouhai_name']}}<br><span>（{{ $v['kouhai_old']  }}）{{$sex[$v['kouhai_sex']]}}</span></p>
                                        </div>
                                    </li>
                                    <li><img src="{{ $v['lesson_class_icon'] }}" class="カテゴリーアイコン"></li>
                                </ul>
                                <div>
                                    <p class="lesson_ttl">{{$v['lesson_title']}}</p>
                                    <p class="target_area">レッスン場所：{{$v['lesson_pos_discuss'] == 0 ? ($v['lesson']['lesson_area_names'] ? implode('/', $v['lesson']['lesson_area_names']) : '') : ($v['lesson_discuss_area'] ? $v['lesson_discuss_area'] : '')}}</p>
                                </div>

                                <div class="kigen_wrap">
                                    <h4>承認期限：{{$v['until_confirm']}}</h4>
                                    <ul class="list_area">
                                        @if(isset($v['req']) && count($v['req']) > 0)
                                            @foreach($v['req'] as $k1=>$v1)
                                                @php $arr = explode('|',$v1); @endphp
                                                <li>
                                                    <div>
                                                        {{$arr[0]}}&nbsp;&nbsp;&nbsp;{{$arr[1]}}
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                        @if(isset($v['hope_time']) )
                                            <li>
                                                <div class="about_attention_02">
                                                    <span class="attention_update_02">{{$v['hope_time']}}</span>
                                                </div>
                                            </li>
                                        @endif
                                        @if(isset($v['confirm']) && count($v['confirm']) > 0)
                                            @foreach($v['confirm'] as $k1=>$v1)
                                                    <li class="expired">
                                                        <h5>承認期限：{{$k1}} </h5>
                                                        @foreach($v1 as $k2=>$v2)
                                                            @php $arr = explode('|',$v2); @endphp
                                                            <p>
                                                                    <span class="dete">
                                                                        {{$arr[1]}}&nbsp;&nbsp;&nbsp;{{$arr[2]}}
                                                                    </span>
                                                                <span class="price">{{$arr[3]}}円</span>
                                                            </p>
                                                        @endforeach
                                                    </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </a>
                    </div>
                  @endforeach
              {{--@else
                  <div class="no-data">検索結果 0件</div>--}}
              @endif
          </section>
         </div><!-- /tab_content -->
    </div><!-- /tabs -->

      </div><!-- /contents -->

    <div id="sample" style="display: none">
        <div class="board_box">
            <a href="#">
                <ul class="teacher_info_03 mt0">
                    <li class="icon_s40"><img src="" alt="プロフィールアイコン"></li>
                    <li class="about_teacher">
                        <div class="profile_name">
                            <p></p>
                        </div>
                    </li>
                    <li><img src="" alt="カテゴリーアイコン"></li>
                </ul>

                <div>
                    <p class="lesson_ttl"></p>
                    <p class="target_area"></p>
                </div>
                <div class="kigen_wrap">
                    <h4></h4>
                    <ul class="list_area">
                    </ul>
                </div>
            </a>
        </div>
    </div>
    <input type="hidden" id="pages1" value="{{$pages1}}">
    <input type="hidden" id="pages2" value="{{$pages2}}">

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

@section('page_js')
	<script>
        var RESERVE_TYPE = {{config('const.request_type.reserve')}};
        var ATTEND_TYPE = {{config('const.request_type.attend')}};
        var pageCount1 = parseInt($('#pages1').val());
        var pageCount2 = parseInt($('#pages2').val());
        var pageCurrent1 = 1;
        var pageCurrent2 = 1;
        var isLoading = false;
        var cur_tab = RESERVE_TYPE;
        $(document).ready(function () {
            if($('#tab-01').attr('checked') == 'checked') {
                cur_tab = RESERVE_TYPE;
            }
            if($('#tab-02').attr('checked') == 'checked') {
                cur_tab = ATTEND_TYPE;
            }
            $('#tab-01').click(function () { cur_tab = RESERVE_TYPE; });
            $('#tab-02').click(function () { cur_tab = ATTEND_TYPE; });
            $('#tab-02').click(function () { cur_tab = ATTEND_TYPE; });
            $(window).scroll(function(){
                var obj;
                if(cur_tab == RESERVE_TYPE) { obj = $("#tab-01_content"); }
                else if(cur_tab == ATTEND_TYPE) { obj = $("#tab-02_content"); }
                var totalHeight = document.documentElement.scrollHeight - $(obj).outerHeight(true);
                var clientHeight = document.documentElement.clientHeight;
                var scrollTop = (document.body && document.body.scrollTop) ? document.body.scrollTop : document.documentElement.scrollTop;
                if ((totalHeight == scrollTop + clientHeight) || (totalHeight <= scrollTop + clientHeight + 3)) {
                    if (isLoading) return;
                    showList(cur_tab);
                }
                return;
            });
        });

        function showList(cur_tab) {
            if(cur_tab == RESERVE_TYPE) {
                if(pageCount1 <= 1) return;
                if(pageCount1 <= pageCurrent1) return;
                pageCurrent1++;
                getAjaxList(cur_tab, pageCurrent1);
            }
            if(cur_tab == ATTEND_TYPE) {
                if(pageCount2 <= 1) return;
                if(pageCount2 <= pageCurrent2) return;
                pageCurrent2++;
                getAjaxList(cur_tab, pageCurrent2);
            }
        }
        function getAjaxList(tab, curPage){
            isLoading = true;
            $.ajax({
                type: "get",
                url: "{{route('user.syutupinn.ajax_req_list')}}" + "/" + tab + "?page=" + curPage,
                dataType: 'json',
                success : function(result)
                {
                    if(result.state == 'success'){
                        var str_html = '';
                        var str_html1 = '';
                        var sex = ['', '女性', '男性'];
                        for( lr_id in result.req_list){
                            var row = result.req_list[lr_id];
                            str_html = row['kouhai_name'] + '<br><span>(' + row['kouhai_old'] + ')' + sex[row['kouhai_sex']] + '</span>';
                            $('#sample .profile_name a').attr('href', '{{route('user.syutupinn.reserve_check')}}/'+row['lr_id']);
                            $('#sample .profile_name p').html(str_html);
                            $('#sample .teacher_info_03 li:nth-child(1) img').attr('src', row['kouhai_avatar'] );
                            $('#sample .teacher_info_03 li:nth-child(3) img').attr('src', row['lesson_class_icon'] );
                            $('#sample .lesson_ttl').html(row['lesson_title'] );
                            if(parseInt(row['lesson_pos_discuss']) == 0)
                                str_html = 'レッスン場所：センパイが指定した場所が入ります';
                           else
                                str_html = 'レッスン場所：コウハイが提案した名称or住所が入ります';
                            $('#sample .target_area').html(str_html );
                            str_html = '承認期限：' + row['req']['until_confirm'];
                            $('#sample .kigen_wrap h4').html(str_html );
                            $('#sample .kigen_wrap ul.list_area').html('');
                            if(row['req'] != undefined && row['req'].length > 0){
                                for(var i=0; i<row['req'].length; i++){
                                    var temp_arr = row['req'][i].split('|');
                                    str_html = '<li>' +
                                        '<div>' + temp_arr[0] + '&nbsp;&nbsp;&nbsp;' + temp_arr[1] +  '</div>' ;
                                    if(tab == RESERVE_TYPE)
                                        str_html += '<div>' + temp_arr[2] + '円</div>' ;
                                    str_html += '</li>';
                                    $('#sample .kigen_wrap ul.list_area').append(str_html );
                                }
                            }

							if(tab == ATTEND_TYPE){
                                str_html = '<li>' +
                                    '<div class="about_attention_02">' +
                                    '<span class="attention_update_02">' + row['hope_time'] + '</span>' +
                                    '</div>' +
                                    '</li>';
                                $('#sample .kigen_wrap ul.list_area').append(str_html );
                            }
							if(row['confirm'] != undefined && row['confirm'].length > 0){
                                for(confirm_date in row['confirm']){
                                    str_html = '<li class="expired">' +
                                        '<h5>承認期限：' + confirm_date + ' </h5>';
                                    for(var i=0; i<row['confirm'][confirm_date].length; i++){
                                        var temp_arr = row['confirm'][confirm_date][i].split('|');
                                        str_html += '<p>' +
                                            '<span class="dete">' +  temp_arr[1] + '&nbsp;&nbsp;&nbsp;' + temp_arr[2] +  '</span>' +
                                            '<span class="price">' + temp_arr[3] + '円</span>' +
                                            '</p>';
                                    }
                                    str_html += '</li>';
                                    $('#sample .kigen_wrap ul.list_area').append(str_html );
                                }
							}
                            str_html1 += $('#sample').html();
                        }
                        if(tab == RESERVE_TYPE) { $('#tab-01_content section').append(str_html1);}
                        if(tab == ATTEND_TYPE) { $('#tab-02_content section').append(str_html1);}
                        isLoading = false;
                    }
                }
            });
        }
	</script>
@endsection


@extends('admin.layouts.app')

@section('title', 'マスタ管理')

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">マスタ管理</label>
            <section>
                <div id="main-contents">
                        <div class="search-result-area">
                            <table class="total bb">
                                <tr>
                                    <th>アクセス数コスト<small>(x)</small></th>
                                    <td>
                                        <input type="number" value="{{ \App\Service\SettingService::getSetting('access_cost', 'float') }}" min="0" placeholder="2.0" data-field="access_cost" class="short cost-value">
                                        <span class="error_text cost-error hide" id="error_access_cost"></span>
                                    </td>
                                    <td>円</td>
                                </tr>
                                <tr>
                                    <th>実施コスト(y)</th>
                                    <td>
                                        <input type="number" value="{{ \App\Service\SettingService::getSetting('implementation_cost', 'float') }}" min="0" placeholder="5.0" data-field="implementation_cost" class="short cost-value">
                                        <span class="error_text cost-error hide" id="error_implementation_cost"></span>
                                    </td>
                                    <td>円</td>
                                </tr>
                            </table>
                            <h2>手数料</h2>
                            <table class="total td-left bo">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>料金/料率</th>
                                    <th>適用条件</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><sup>料率</sup><em>A</em></td>
                                    <td>150円</td>
                                    <td class="text-left">料率BまたはCの全額が150円を下回った場合に適用</td>
                                </tr>
                                <tr>
                                    <td><sup>料率</sup><em>B</em></td>
                                    <td><small>決算金額の</small><em>7%</em></td>
                                    <td class="text-left">同一の後輩と前回のレッスン日から14日以内にレッスンを行った場合に適用</td>
                                </tr>
                                <tr>
                                    <td><sup>料率</sup><em>C</em></td>
                                    <td><small>決算金額の</small><em>20%</em></td>
                                    <td class="text-left">後輩との初回レッスン、または同一の後輩との前回のレッスン日から15日以降にレッスンを行った場合に適用</td>
                                </tr>
                                <tr>
                                    <td>出張交通費</td>
                                    <td><small>出張交通費の</small><em>7%</em></td>
                                    <td class="text-left">出張交通費にかかる手数料</td>
                                </tr>
                                </tbody>
                            </table>
                            <h2>サービスカテゴリー</h2>
                            <table class="total bo">
                                @foreach(\App\Service\LessonClassService::getAllLessonClasses() as $key => $obj_lesson)
                                    @if($loop->odd)
                                        <tr>
                                    @endif
                                            <th>{{ $key+1 }}</th>
                                            <td class="text-left">{{ $obj_lesson->class_name }}</td>
                                    @if($loop->even)
                                        </tr>
                                    @endif
                                @endforeach
                            </table>

                            <h2>評価加重割合</h2>
                            <table class="total bb th-pr senpai" id="senpai_reviews">
                                <tbody>
                                <tr>
                                    <th><h3>先輩の評価項目</h3></th>
                                    <td><button type="button" class="edit">編集</button></td>
                                </tr>
                                <tr>
                                    <th class="text-left">当日のレッスン内容は出品内容と同じでしたか?</th>
                                    <td>
                                        <input type="number" value="{{ \App\Service\SettingService::getSetting('s_review_content', 'float') }}" min="0" placeholder="1.0" data-field="s_review_content" class="short no-border senapi-reviews">
                                        <span class="error_text senapi-review-error hide" id="error_s_review_content"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-left">遅刻などなく正しくレッスン時間が守られていましたか?</th>
                                    <td>
                                        <input type="number" value="{{ \App\Service\SettingService::getSetting('s_review_time', 'float') }}" min="0" placeholder="1.0" data-field="s_review_time" class="short no-border senapi-reviews">
                                        <span class="error_text senapi-review-error hide" id="error_s_review_time"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-left">レッスン当日までのトークルームでの対応は丁寧で分かりやすかったですか?</th>
                                    <td>
                                        <input type="number" value="{{ \App\Service\SettingService::getSetting('s_review_talkroom', 'float') }}" min="0" placeholder="1.0" data-field="s_review_talkroom" class="short no-border senapi-reviews">
                                        <span class="error_text senapi-review-error hide" id="error_s_review_talkroom"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-left">レッスンを実際に受けてみて、この価格設定に納得できましたか?</th>
                                    <td>
                                        <input type="number" value="{{ \App\Service\SettingService::getSetting('s_review_price', 'float') }}" min="0" placeholder="1.0" data-field="s_review_price" class="short no-border senapi-reviews">
                                        <span class="error_text senapi-review-error hide" id="error_s_review_price"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-left">レッスン中の言葉遣いや態度などは適切でしたか?</th>
                                    <td>
                                        <input type="number" value="{{ \App\Service\SettingService::getSetting('s_review_manner', 'float') }}" min="0" placeholder="1.0" data-field="s_review_manner" class="short no-border senapi-reviews">
                                        <span class="error_text senapi-review-error hide" id="error_s_review_manner"></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table class="total bb th-pr kouhai"  id="kouhai_reviews">
                                <tbody>
                                <tr>
                                    <th><h3>後輩の評価項目</h3></th>
                                    <td><button type="button" class="edit">編集</button></td>
                                </tr>
                                <tr>
                                    <th class="text-left">遅刻などなく、開始・終了時刻がしっかり守られてましたか?</th>
                                    <td>
                                        <input type="number" value="{{ \App\Service\SettingService::getSetting('k_review_time', 'float') }}" min="0" placeholder="1.0" data-field="k_review_time" class="short no-border kouhai-reviews">
                                        <span class="error_text kouhai-review-error hide" id="error_k_review_time"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-left">レッスンに関係のないプライベートなことまでしつこく質問されたりはしませんでしたか?</th>
                                    <td>
                                        <input type="number" value="{{ \App\Service\SettingService::getSetting('k_review_question', 'float') }}" min="0" placeholder="1.0" data-field="k_review_question" class="short no-border kouhai-reviews">
                                        <span class="error_text kouhai-review-error hide" id="error_k_review_question"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-left">トークルームやレッスン当日の言葉遣いや態度は丁寧でしたか?</th>
                                    <td>
                                        <input type="number" value="{{ \App\Service\SettingService::getSetting('k_review_talkroom', 'float') }}" min="0" placeholder="1.0" data-field="k_review_talkroom" class="short no-border kouhai-reviews">
                                        <span class="error_text kouhai-review-error hide" id="error_k_review_talkroom"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-left">出品内容と異なるサービスをお願いされたりすることはありませんでしたか?</th>
                                    <td>
                                        <input type="number" value="{{ \App\Service\SettingService::getSetting('k_review_other', 'float') }}" min="0" placeholder="1.0" data-field="k_review_other" class="short no-border kouhai-reviews">
                                        <span class="error_text kouhai-review-error hide" id="error_k_review_other"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-left">機会があればまたこの後輩とレッスンを行ってみたいと感じましたか?</th>
                                    <td>
                                        <input type="number" value="{{ \App\Service\SettingService::getSetting('k_review_again', 'float') }}" min="0" placeholder="1.0" data-field="k_review_again" class="short no-border kouhai-reviews">
                                        <span class="error_text kouhai-review-error hide" id="error_k_review_again"></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <h2>メインビジュアル</h2>
                            <ul class="btn-list mt0">
                                <li><button type="button" onclick="location.href='{{ route('admin.master.text_master', ['type'=>'invite_friend']) }}'"><i>1. </i>友達招待</button></li>
                                @foreach(config('const.main_visuals') as $key => $title)
                                    <li><button type="button" onclick="location.href='{{ route('admin.master.main_visual', ['type'=>$key]) }}'"><i>{{ $loop->index + 2 }}. </i>{{ $title }}</button></li>
                                @endforeach
                                {{--<li><button type="button" onclick="location.href='edit.php'"><i>5.</i></button></li>--}}
                            </ul>
                            <h2>規約等</h2>
                            <ul class="btn-list mt0">
                                @foreach(config('const.text_contents') as $key => $title)
                                    @if($loop->index == 0)
                                        @continue
                                    @endif
                                    <li><button type="button" onclick="location.href='{{ route('admin.master.text_master', ['type'=>$key]) }}'"><i>{{ $loop->index }}. </i>{{ $title }}</button></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
            </section>
        </div>
        <!-- /tabs -->
@endsection

@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <style>
        section {
            padding-top: 10px !important;
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
    </style>
@endsection

@section('page_js')
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.senpai input[type="number"]').prop('disabled', true);
            $('.senpai .edit').on('click', function () {
                if ($(this).text() === '編集') {
                    $('.senpai input[type="number"]').removeClass('no-border');
                    $('.senpai input[type="number"]').prop('disabled', false);
                    $(this).text('完了');
                } else {
                    setReviewValues('senpai');
                }
            });

            $('.kouhai input[type="number"]').prop('disabled', true);
            $('.kouhai .edit').on('click', function () {
                if ($(this).text() === '編集') {
                    $('.kouhai input[type="number"]').removeClass('no-border');
                    $('.kouhai input[type="number"]').prop('disabled', false);
                    $(this).text('完了');
                } else {
                    setReviewValues('kouhai');
                }
            });

            $('.cost-value').on('change', function () {
                let cost = $(this).val();
                let field = $(this).data('field');

                $.ajax({
                    type: "post",
                    url: "{{route('admin.master.set_cost')}}",
                    data : {
                        _token: "{{ csrf_token() }}",
                        field: field,
                        cost: cost
                    },
                    dataType: 'json',
                    success : function(result) {
                        if(result.result_code == 'failed') {
                            let error = result.errors.cost[0];
                            $('#error_' + field).text(error);
                            $('#error_' + field).removeClass('hide');
                        } else {
                            $('#error_' + field).addClass('hide');
                        }
                    }
                });
            });
            function setReviewValues(tb_where='senpai') {
                let params = {};
                let tb_obj = tb_where == 'senpai' ? $('#senpai_reviews tr') : $('#kouhai_reviews tr')
                tb_obj.each(function(){
                    let inputs = $(this).find('input');
                    inputs.each(function(){
                        params[$(this).data('field')]=this.value;
                    });
                });

                params['_token'] = "{{ csrf_token() }}";
                $.ajax({
                    type: "post",
                    url: "{{route('admin.master.set_reviews')}}",
                    data : params,
                    dataType: 'json',
                    success : function(result) {
                        if(result.result_code == 'failed') {
                            let errors = result.errors;
                           $.each(errors, function (key, value) {
                               $('#error_' + key).text(value[0]);
                               $('#error_' + key).removeClass('hide');
                           });
                        } else {
                            if(tb_where == 'senpai') {
                                $('.senpai input[type="number"]').addClass('no-border');
                                $('.senpai input[type="number"]').prop('disabled', true);
                                $('.senpai .edit').text('編集');

                                $('.senapi-review-error').addClass('hide');
                            } else {
                                $('.kouhai input[type="number"]').addClass('no-border');
                                $('.kouhai input[type="number"]').prop('disabled', true);
                                $('.kouhai .edit').text('編集');
                                $('.kouhai-review-error').addClass('hide');
                            }
                        }
                    }
                });
            }
        });

    </script>
@endsection

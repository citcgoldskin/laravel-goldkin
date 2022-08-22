@extends('user.layouts.app')

@section('title', 'やることリスト')

@section('content')

    @include('user.layouts.header_info')
    <div id="contents">

        <div class="list-area">

            <ul class="todo_list">
                <li>
                    <a href="">
                        <div class="icon-area">
                            <img src="{{ asset('assets/user/img/icon_02.svg') }}" alt="やることリストアイコン">
                        </div>
                        <div class="text-area">
                            <div class="text-small">
                                <div class="color-kouhai">コウハイ</div>
                                <div>2021/01/24</div>
                            </div>
                            <p>松田さんがあなたの予約を承認しました。内容を確認して期限内に購入してください。</p>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="">
                        <div class="icon-area">
                            <img src="{{ asset('assets/user/img/icon_02.svg') }}" alt="やることリストアイコン">
                        </div>
                        <div class="text-area">
                            <div class="text-small">
                                <div class="color-senpai">センパイ</div>
                                <div>2021/01/24</div>
                            </div>
                            <p>高橋さんから予約リクエストが届きました。内容を確認して期限内に承認または辞退をしてください。</p>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="">
                        <div class="icon-area">
                            <img src="{{ asset('assets/user/img/icon_02.svg') }}" alt="やることリストアイコン">
                        </div>
                        <div class="text-area">
                            <div class="text-small">
                                <div class="color-senpai">センパイ</div>
                                <div>2021/01/24</div>
                            </div>
                            <p>あなたが提案した●●さんの募集が変更されました。内容を確認しもう一度提案してください。</p>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="">
                        <div class="icon-area">
                            <img src="{{ asset('assets/user/img/icon_02.svg') }}" alt="やることリストアイコン">
                        </div>
                        <div class="text-area">
                            <div class="text-small">
                                <div class="color-kouhai">コウハイ</div>
                                <div>2021/01/24</div>
                            </div>
                            <p>あなたの募集に対して●●さんから提案が届きました。内容を確認してください。</p>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="">
                        <div class="icon-area">
                            <img src="{{ asset('assets/user/img/icon_02.svg') }}" alt="やることリストアイコン">
                        </div>
                        <div class="text-area">
                            <div class="text-small">
                                <div class="color-kouhai">コウハイ</div>
                                <div>2021/01/24</div>
                            </div>
                            <p>●●さんがあなたの予約を辞退しました。<br>
                                内容を確認し、日時を変更するか他のセンパイにお願いしてください。</p>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="">
                        <div class="icon-area">
                            <img src="{{ asset('assets/user/img/icon_02.svg') }}" alt="やることリストアイコン">
                        </div>
                        <div class="text-area">
                            <div class="text-small">
                                <div class="color-kouhai">コウハイ</div>
                                <div>2021/01/24</div>
                            </div>
                            <p>●●さんがあなたの出勤リクエストを辞退しました。内容を確認し、日時を変更するか他のセンパイにお願いしてください。</p>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="">
                        <div class="icon-area">
                            <img src="{{ asset('assets/user/img/icon_02.svg') }}" alt="やることリストアイコン">
                        </div>
                        <div class="text-area">
                            <div class="text-small">
                                <div class="color-senpai">センパイ</div>
                                <div>2021/01/24</div>
                            </div>
                            <p>●●さんがあなたのレッスンを購入しました。トークルームを確認してください。</p>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="">
                        <div class="icon-area">
                            <img src="{{ asset('assets/user/img/icon_02.svg') }}" alt="やることリストアイコン">
                        </div>
                        <div class="text-area">
                            <div class="text-small">
                                <div class="color-senpai">センパイ</div>
                                <div>2021/01/24</div>
                            </div>
                            <p>●●さんがあなたの提案を購入しました。<br>
                                トークルームを確認してください。</p>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="">
                        <div class="icon-area">
                            <img src="{{ asset('assets/user/img/icon_02.svg') }}" alt="やることリストアイコン">
                        </div>
                        <div class="text-area">
                            <div class="text-small">
                                <div class="color-senpai">センパイ</div>
                                <div>2021/01/24</div>
                            </div>
                            <p>●●さんから出勤リクエストが届きました。内容を確認し、承認又は辞退をしてください。</p>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="">
                        <div class="icon-area">
                            <img src="{{ asset('assets/user/img/icon_02.svg') }}" alt="やることリストアイコン">
                        </div>
                        <div class="text-area">
                            <div class="text-small">
                                <div class="color-kouhai">コウハイ</div>
                                <div>2021/01/24</div>
                            </div>
                            <p>●●さんがあなたの出勤リクエストを承認しました。内容を確認して期限内に購入してください。</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

    </div><!-- /contents -->

@endsection

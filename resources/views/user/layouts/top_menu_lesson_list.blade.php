@if (count($reserves)> 0)
    <div id="top-menu" class="talkroom_wrap">
        <ul class="display_area icon_speaker">
            <li>
                <input id="acd-check1" class="acd-check" type="checkbox">
                <label class="acd-label" for="acd-check1">
                    <p>
                        予約中のレッスン
                    </p>
                </label>
                <div class="acd-content top-menu-content">
                    <div class="grad-wrap">

                        @if (count($reserves)> 4)
                        <input id="trigger1" class="grad-trigger" type="checkbox">
                        <label class="grad-btn" for="trigger1">さらに表示</label>
                        @endif
                        <div class="grad-item">
                            @php
                                foreach( $reserves as $k => $v) {
                                    echo '<p>'.$v['reserve'].'</p>';
                                }
                            @endphp
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@endif


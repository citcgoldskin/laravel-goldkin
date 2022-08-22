<?php
namespace App\Models;

class Setting extends BaseModel
{

    const ACCESS_COST = 'access_cost';  //アクセス数コスト値
    const IMPLEMENTATION_COST = 'implementation_cost'; //実施コスト値
    const SENPAI_REVIEW_CONTENT = 's_review_content'; //当日のレッスン内容は出品内容と同じでしたか?
    const SENPAI_REVIEW_TIME = 's_review_time'; //遅刻などなく正しくレッスン時間が守られていましたか?
    const SENPAI_REVIEW_TALKROOM = 's_review_talkroom'; //レッスン当日までのトークルームでの対応は丁寧で分かりやすかったですか?
    const SENPAI_REVIEW_PRICE = 's_review_price'; //レッスンを実際に受けてみて、この価格設定に納得できましたか?
    const SENPAI_REVIEW_MANNER = 's_review_manner'; //レッスン中の言葉遣いや態度などは適切でしたか?
    const KOIHAI_REVIEW_TIME = 'k_review_time'; //遅刻などなく、開始・終了時刻がしっかり守られてましたか?
    const KOIHAI_REVIEW_QUESTION = 'k_review_question'; //レッスンに関係のないプライベートなことまでしつこく質問されたりはしませんでしたか?
    const KOIHAI_REVIEW_TALKROOM = 'k_review_talkroom'; //トークルームやレッスン当日の言葉遣いや態度は丁寧でしたか?
    const KOIHAI_REVIEW_OTHER = 'k_review_other'; //出品内容と異なるサービスをお願いされたりすることはありませんでしたか?
    const KOIHAI_REVIEW_AGAIN = 'k_review_again'; //機会があればまたこの後輩とレッスンを行ってみたいと感じましたか?

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'value'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];

}

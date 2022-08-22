<?php

return [
    'service_manage'=> [
        'lesson_agree' => [
            'alert_title' => "レッスン承認のお知らせ",
            'alert_text' => "いつもセンパイをご利用いただきありがとうございます。\n申請いただきましたレッスン「:lesson_title」について、出品を承認させていただきます。\n引き続きセンパイをご利用ください。\n\n",
        ],
        'lesson_disagree' => [
            'alert_title' => "申請いただいたレッスン内容について",
            'alert_text' => "いつもセンパイをご利用いただきありがとうございます。\n申請いただきましたレッスン「:lesson_title」について、以下の点を修正上、再度申請をお願いします。\n\n"
        ],
        'lesson_history' => [
            'alert_title' => "レッスンの取り扱いについて",
            'alert_text' => "レッスンの取引について運営が不適切と判断したため無料キャンセルとさせていただきます。\n詳しくは利用規約をご覧ください。\n\n"
        ]
    ],
    'patrol'=> [
        'request' => [
            'alert_title' => "リクエストの取り扱いについて",
            'alert_text' => "このリクエストについて\n運営が不適切と判断したため\n削除をさせていただきます。\n詳しくは利用規約をご覧ください。\n\n",
        ]
    ]
];
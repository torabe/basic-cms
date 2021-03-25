<?php

return [
    'field_types' => [
        ['text' => 'テキスト', 'value' => 'text'],
        ['text' => '日付', 'value' => 'date'],
        ['text' => '日付+時刻', 'value' => 'datetime'],
        ['text' => 'テキストエリア', 'value' => 'textarea'],
        ['text' => 'HTML', 'value' => 'wysiwyg'],
        ['text' => '画像', 'value' => 'image'],
        ['text' => 'ファイル', 'value' => 'file'],
        ['text' => 'リンク', 'value' => 'link'],
        // ['text' => 'ループ', 'value' => 'loop']
    ],
    'field_validates' => [
        'text' => [
            ['text' => '必須入力', 'value' => 'required'],
            ['text' => 'アルファベットのみ', 'value' => 'alpha'],
            ['text' => 'アルファベット+数字', 'value' => 'alpha_num'],
            ['text' => 'アルファベット+数字+ハイフン+下線', 'value' => 'alpha_dash'],
            ['text' => '数字のみ', 'value' => 'numeric'],
            ['text' => 'メールアドレス', 'value' => 'email:rfc'],
            ['text' => 'URL', 'value' => 'url'],
        ],
        'date' => [
            ['text' => '必須入力', 'value' => 'required'],
            ['text' => '現在時刻以前', 'value' => 'before:"now"'],
            ['text' => '現在時刻以降', 'value' => 'after:"now"'],
        ],
        'datetime' => [
            ['text' => '必須入力', 'value' => 'required'],
            ['text' => '現在時刻以前', 'value' => 'before:"now"'],
            ['text' => '現在時刻以降', 'value' => 'after:"now"'],
        ],
        'textarea' => [
            ['text' => '必須入力', 'value' => 'required'],
        ],
        'wysiwyg' => [
            ['text' => '必須入力', 'value' => 'required'],
        ],
        'image' => [
            ['text' => '必須入力', 'value' => 'required'],
        ],
        'file' => [
            ['text' => '必須入力', 'value' => 'required'],
        ],
        'link' => [
            ['text' => '必須入力', 'value' => 'required'],
        ],
        'loop' => [
            ['text' => '必須入力', 'value' => 'required'],
        ],
    ],
];

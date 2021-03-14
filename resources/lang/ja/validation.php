<?php

return [

    /*
      |--------------------------------------------------------------------------
      | Validation Language Lines
      | 検証言語
      |--------------------------------------------------------------------------
      |
      | The following language lines contain the default error messages used by
      | the validator class. Some of these rules have multiple versions such
      | as the size rules. Feel free to tweak each of these messages here.
      |
      | 次の言語行には、バリデータークラスで使用されるデフォルトのエラーメッセージが含まれています。
      | これらの規則の中には、サイズ規則などの複数のバージョンがあります。
      | これらのメッセージのそれぞれをここで微調整してください。
    */

    'accepted'             => ':attributeが未承認です。',
    'active_url'           => ':attributeは有効なURLではありません。',
    'after'                => ':attributeは:dateより後の日付にしてください。',
    'after_or_equal'       => ':attributeは:date以降の日付にしてください。',
    'alpha'                => ':attributeは英字のみ有効です。',
    'alpha_dash'           => ':attributeは「英字」「数字」「-(ダッシュ)」「_(下線)」のみ有効です。',
    'alpha_num'            => ':attributeは「英字」「数字」のみ有効です。',
    'array'                => ':attributeは配列タイプのみ有効です。',
    'before'               => ':attributeは:dateより前の日付にしてください。',
    'before_or_equal'      => ':attributeは:date以前の日付にしてください。',
    'between'              => [
        'numeric' => ':attributeは:min ～ :maxまでの数値まで有効です。',
        'file'    => ':attributeは:min ～ :maxキロバイトまで有効です。',
        'string'  => ':attributeは:min ～ :max文字まで有効です。',
        'array'   => ':attributeは:min ～ :max個まで有効です。',
    ],
    'boolean'              => ':attributeの値は、true もしくは false のみ有効です。',
    'confirmed'            => ':attributeを確認用と一致させてください。',
    'date'                 => ':attributeを有効な日付形式にしてください。',
    'date_format'          => ':attributeを:format書式と一致させてください。',
    'different'            => ':attributeを:otherと違うものにしてください。',
    'digits'               => ':attributeは:digits桁のみ有効です。',
    'digits_between'       => ':attributeは:min ～ :max桁のみ有効です。',
    'dimensions'           => ':attributeはルールに合致する画像サイズのみ有効です。',
    'distinct'             => ':attributeに重複している値があります。',
    'email'                => ':attributeはメールアドレスの書式のみ有効です。',
    'exists'               => ':attributeは無効な値です。',
    'file'                 => ':attributeはアップロード出来ないファイルです。',
    'filled'               => ':attributeは値を入力してください。',
    'image'                => ':attribute画像は「jpg」「png」「bmp」「gif」「svg」のみ有効です。',
    'in'                   => ':attributeは無効な値です。',
    'in_array'             => ':attributeは:otherと一致する必要があります。',
    'integer'              => ':attributeは整数のみ有効です。',
    'ip'                   => ':attributeはIPアドレスの書式のみ有効です。',
    'ipv4'                 => ':attributeはIPアドレス(IPv4)の書式のみ有効です。',
    'ipv6'                 => ':attributeはIPアドレス(IPv6)の書式のみ有効です。',
    'json'                 => ':attributeは正しいJSON文字列のみ有効です。',
    'max'                  => [
        'numeric' => ':attributeは:max以下のみ有効です。',
        'file'    => ':attributeは:maxKB以下のファイルのみ有効です。',
        'string'  => ':attributeは:max文字以下のみ有効です。',
        'array'   => ':attributeは:max個以下のみ有効です。',
    ],
    'mimes'                => ':attributeは:valuesタイプのみ有効です。',
    'mimetypes'            => ':attributeは:valuesタイプのみ有効です。',
    'min'                  => [
        'numeric' => ':attributeは:min以上のみ有効です。',
        'file'    => ':attributeは:minKB以上のファイルのみ有効です。',
        'string'  => ':attributeは:min文字以上のみ有効です。',
        'array'   => ':attributeは:min個以上のみ有効です。',
    ],
    'not_in'               => ':attributeは無効な値です。',
    'numeric'              => ':attributeは数字のみ有効です。',
    'present'              => ':attributeが存在しません。',
    'regex'                => ':attributeは無効な値です。',
    'required'             => ':attributeは必須です。',
    'required_if'          => ':attributeは:otherが:valueで入力されている場合は必須です。',
    'required_unless'      => ':attributeは:otherが:valuesでなければ必須です。',
    'required_with'        => ':attributeは:valuesが入力されている場合は必須です。',
    'required_with_all'    => ':attributeは:valuesが入力されている場合は必須です。',
    'required_without'     => ':attributeは:valuesが入力されていない場合は必須です。',
    'required_without_all' => ':attributeは:valuesが入力されていない場合は必須です。',
    'same'                 => ':attributeは:otherと同じ場合のみ有効です。',
    'size'                 => [
        'numeric' => ':attributeは:sizeのみ有効です。',
        'file'    => ':attributeは:sizeKBのみ有効です。',
        'string'  => ':attributeは:size文字のみ有効です。',
        'array'   => ':attributeは:size個のみ有効です。',
    ],
    'string'               => ':attributeは文字列のみ有効です。',
    'timezone'             => ':attributeは正しいタイムゾーンのみ有効です。',
    'unique'               => ':attributeは既に存在します。',
    'uploaded'             => ':attributeアップロードに失敗しました。',
    'url'                  => ':attributeは正しいURL書式のみ有効です。',
    'within_integer_decimal_digit' => ":attributeは整数:max_integer桁/小数点以下:max_decimal桁以内で入力してください。",
    'post'         => ':attributeは郵便番号形式で入力してください。',
    'tel'         => ':attributeは電話番号形式で入力してください。',
    'within_decimal_digit' => ":attributeは小数点以下:max桁以内で入力してください。",
    'max_half_width'         => ':attributeは全角:max_full(半角:max_half)以内で入力してください。',

    /*
      |--------------------------------------------------------------------------
      | Custom Validation Language Lines
      | カスタム検証言語
      |--------------------------------------------------------------------------
      |
      | Here you may specify custom validation messages for attributes using the
      | convention "attribute.rule" to name the lines. This makes it quick to
      | specify a specific custom language line for a given attribute rule.
      |
      | ここでは、行に名前を付けるために "attribute.rule"という規則を使って属性のカスタム
      | 検証メッセージを指定することができます。 これにより、特定の属性ルールに対して特定の
      | カスタム言語行をすばやく指定できます。
      |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
      |--------------------------------------------------------------------------
      | Custom Validation Attributes
      | カスタム検証属性
      |--------------------------------------------------------------------------
      |
      | The following language lines are used to swap attribute place-holders
      | with something more reader friendly such as E-Mail Address instead
      | of "email". This simply helps us make messages a little cleaner.
      |
      | 次の言語行は、属性プレースホルダを「email」ではなく「E-Mail Address」などの
      | 読みやすいものと交換するために使用されます。
      |
    */

    'attributes' => [],

];

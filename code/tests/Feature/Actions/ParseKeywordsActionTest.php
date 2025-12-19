<?php

/**
 * @see ParseKeywordsAction
 */

use App\Actions\ParseKeywordsAction;

dataset(
    'キーワード指定のパターン',
    [
        [
            '山田　太郎', // input
            ['山田', '太郎'], // expect
        ],
        [
            '　山田　太郎', // input
            ['山田', '太郎'], // expect
        ],
        [
            '　山田　　太郎', // input
            ['山田', '太郎'], // expect
        ],
        [
            '　鳥取市　山田 太郎', // input
            ['鳥取市', '山田', '太郎'], // expect
        ],

        [
            '山田 太郎', // input
            ['山田', '太郎'], // expect
        ],
        [
            ' 山田 太郎', // input
            ['山田', '太郎'], // expect
        ],
        [
            ' 山田  太郎', // input
            ['山田', '太郎'],
        ],
        [
            ' 鳥取市 山田 太郎', // input
            ['鳥取市', '山田', '太郎'],
        ],

        [
            "山田\n太郎", // input
            ['山田', '太郎'],
        ],
        [
            "\r山田\t太郎", // input
            ['山田', '太郎'],
        ],
        [
            "\n山田 \n太郎", // input
            ['山田', '太郎'], // expect
        ],
        [
            "  鳥取市\r\n山田\t太郎\t", // input
            ['鳥取市', '山田', '太郎'], // expect
        ],
    ]
);

test(
    'スペース区切りのキーワードを string 一つずつに分割する',

    /**
     * @throws Exception
     */
    function (string $input, array $expect) {
        $actual = app(ParseKeywordsAction::class)
            ->handle($input);

        expect($actual)
            ->toBe($expect);
    }
)->with('キーワード指定のパターン');

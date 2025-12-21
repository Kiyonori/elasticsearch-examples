<?php

namespace App\Actions;

final readonly class ParseKeywordsAction
{
    /**
     * スペース区切りのキーワードを string 一つずつに分割する
     *
     * @param  string  $keywords  スペース区切りのキーワード (例) '鳥取市 山田 太郎'
     * @return array<int, string>
     */
    public function handle(
        string $keywords,
    ): array {
        $normalized = mb_trim(
            preg_replace(
                pattern: '/[　\s\t\r\n]+/',
                replacement: ' ',
                subject: $keywords,
            )
        );

        return explode(
            separator: ' ',
            string: $normalized,
        );
    }
}

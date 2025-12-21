<?php

namespace App\Http\Requests\Elasticsearch\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Elasticsearch 内の users インデックスを検索するときの検索条件のバリデーション
 */
class IndexUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keywords' => [
                'required',
                'string',
                'min:2',
            ],

            'size' => [
                'required',
                'int',
                'min:1',
            ],

            'search_after' => [
                'nullable',
                'string',
            ],
        ];
    }
}

<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * users を検索するときの検索条件のバリデーション
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
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'birth_date',
        'breed_id',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
        ];
    }
}

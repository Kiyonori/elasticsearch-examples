<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'last_name',
        'last_kana_name',
        'first_name',
        'first_kana_name',
        'email',
        'password',
        'prefecture',
        'city',
        'street_address',
        'phone_number',
        'memo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function pets(): HasMany
    {
        return $this->hasMany(
            Pet::class,
        );
    }
}

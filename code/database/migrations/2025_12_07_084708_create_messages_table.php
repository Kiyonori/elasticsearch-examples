<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('sender_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table
                ->foreignId('receiver_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('message');

            $table
                ->timestamp('read_at')
                ->nullable()
                ->comment('メッセージ開封日時');

            $table->timestamps();
            $table->softDeletes();
        });
    }
};

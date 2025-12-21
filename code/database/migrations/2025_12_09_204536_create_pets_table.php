<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table
                ->string(column: 'name', length: 50)
                ->comment('ペットの名前');

            $table
                ->date('birth_date')
                ->comment('ペットの誕生日');

            $table
                ->foreignId('breed_id')
                ->constrained('breeds')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};

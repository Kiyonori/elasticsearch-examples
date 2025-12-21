<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('breeds', function (Blueprint $table) {
            $table->id();

            $table
                ->string(column: 'breed', length: 50)
                ->comment('品種');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('breeds');
    }
};

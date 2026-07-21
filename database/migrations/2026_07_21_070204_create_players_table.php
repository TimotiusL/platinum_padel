<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('phone');
            $table->date('birth_date')->nullable();

            $table->enum('gender', [
                'male',
                'female'
            ])->nullable();

            $table->string('city')->nullable();

            $table->string('photo')->nullable();

            $table->integer('ranking_point')
                ->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};

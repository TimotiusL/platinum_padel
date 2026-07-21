<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_scores', function (Blueprint $table) {

            $table->id();

            $table->foreignId('match_id')
                ->constrained('tournament_matches')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('set_number');

            $table->unsignedTinyInteger('team_a_score');

            $table->unsignedTinyInteger('team_b_score');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_scores');
    }
};

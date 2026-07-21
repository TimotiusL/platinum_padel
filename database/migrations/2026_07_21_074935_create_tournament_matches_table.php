<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tournament_matches', function (Blueprint $table) {

        $table->id();

        $table->foreignId('category_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('team_a_id')
            ->constrained('teams')
            ->cascadeOnDelete();

        $table->foreignId('team_b_id')
            ->constrained('teams')
            ->cascadeOnDelete();

        $table->foreignId('winner_team_id')
            ->nullable()
            ->constrained('teams')
            ->nullOnDelete();

        $table->string('round');

        $table->string('court')->nullable();

        $table->dateTime('match_date')->nullable();

        $table->enum('status', [
            'scheduled',
            'ongoing',
            'finished'
        ])->default('scheduled');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_matches');
    }
};

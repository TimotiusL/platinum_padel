<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tournament_matches', function (Blueprint $table) {
            $table->unsignedTinyInteger('score_team_a')->default(0);
            $table->unsignedTinyInteger('score_team_b')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('tournament_matches', function (Blueprint $table) {
            $table->dropColumn([
                'score_team_a',
                'score_team_b'
            ]);
        });
    }
};

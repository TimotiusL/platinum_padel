<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('match_sets', function (Blueprint $table) {
            $table->unsignedBigInteger('winner_team_id')
                ->nullable()
                ->after('score_team_b');
        });
    }

    public function down(): void
    {
        Schema::table('match_sets', function (Blueprint $table) {
            $table->dropColumn('winner_team_id');
        });
    }
};
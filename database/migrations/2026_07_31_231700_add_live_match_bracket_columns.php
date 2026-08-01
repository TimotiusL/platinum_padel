<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tournament_matches', 'score_team_a')) {
            Schema::table('tournament_matches', function (Blueprint $table) {
                $table->unsignedTinyInteger('score_team_a')->default(0)->after('status');
            });
        }

        if (!Schema::hasColumn('tournament_matches', 'score_team_b')) {
            Schema::table('tournament_matches', function (Blueprint $table) {
                $table->unsignedTinyInteger('score_team_b')->default(0)->after('score_team_a');
            });
        }

        if (!Schema::hasColumn('tournament_matches', 'bracket_order')) {
            Schema::table('tournament_matches', function (Blueprint $table) {
                $table->unsignedInteger('bracket_order')->nullable()->after('round');
                $table->index(['category_id', 'round', 'bracket_order'], 'tm_category_round_order_idx');
            });
        }

        foreach ([
            'played' => 0,
            'win' => 0,
            'lose' => 0,
            'points' => 0,
            'score_for' => 0,
            'score_against' => 0,
        ] as $column => $default) {
            if (!Schema::hasColumn('teams', $column)) {
                Schema::table('teams', function (Blueprint $table) use ($column, $default) {
                    $table->unsignedInteger($column)->default($default);
                });
            }
        }
    }

    public function down(): void
    {
        $matchColumns = collect(['score_team_a', 'score_team_b', 'bracket_order'])
            ->filter(fn ($column) => Schema::hasColumn('tournament_matches', $column))
            ->all();

        if ($matchColumns) {
            Schema::table('tournament_matches', function (Blueprint $table) use ($matchColumns) {
                if (in_array('bracket_order', $matchColumns, true)) {
                    $table->dropIndex('tm_category_round_order_idx');
                }
                $table->dropColumn($matchColumns);
            });
        }

        $teamColumns = collect(['played', 'win', 'lose', 'points', 'score_for', 'score_against'])
            ->filter(fn ($column) => Schema::hasColumn('teams', $column))
            ->all();

        if ($teamColumns) {
            Schema::table('teams', fn (Blueprint $table) => $table->dropColumn($teamColumns));
        }
    }
};

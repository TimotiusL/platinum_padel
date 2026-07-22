<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('match_tournaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments');
            $table->foreignId('team1_id')->constrained('teams');
            $table->foreignId('team2_id')->constrained('teams');
            $table->integer('score1')->default(0);
            $table->integer('score2')->default(0);
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->string('stage'); 
            $table->string('round')->nullable();
            $table->dateTime('scheduled_at');
            $table->dateTime('played_at')->nullable();
            $table->json('score_history')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('match_tournaments');
    }
};
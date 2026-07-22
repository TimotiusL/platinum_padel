<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // A1, A2, etc
            $table->foreignId('player1_id')->constrained('players');
            $table->foreignId('player2_id')->constrained('players');
            $table->foreignId('tournament_id')->constrained('tournaments');
            $table->string('group_code')->nullable(); // A, B, C
            $table->integer('points')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
};
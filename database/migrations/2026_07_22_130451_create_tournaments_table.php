<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('badge')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('venue');
            $table->string('venue_sub')->nullable();
            $table->string('location');
            $table->string('prize')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'finished'])->default('upcoming');
            $table->string('poster')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tournaments');
    }
};
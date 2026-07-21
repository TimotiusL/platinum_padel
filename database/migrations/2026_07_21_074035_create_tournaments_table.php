<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {

            $table->id();

            $table->string('title');

            $table->text('description')->nullable();

            $table->string('poster')->nullable();

            $table->string('venue');

            $table->string('location');

            $table->date('start_date');

            $table->date('end_date');

            $table->date('registration_deadline');

            $table->enum('status', [
                'upcoming',
                'ongoing',
                'finished'
            ])->default('upcoming');

            $table->decimal('prize_pool', 12, 2)->default(0);

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};

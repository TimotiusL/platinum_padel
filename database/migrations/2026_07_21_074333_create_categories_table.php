<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tournament_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->enum('gender', [
                'male',
                'female',
                'mixed',
            ]);

            $table->decimal('entry_fee', 10, 2)->default(0);

            $table->unsignedInteger('max_team');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

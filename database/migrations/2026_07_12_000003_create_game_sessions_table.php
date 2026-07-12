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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id')->constrained()->onDelete('cascade');
            $table->string('game_type')->default('catch_the_character');
            $table->integer('score')->default(0);
            $table->integer('hits')->default(0);
            $table->integer('mistakes')->default(0);
            $table->decimal('accuracy', 5, 2)->default(0);
            $table->integer('duration')->default(0)->comment('Duración en segundos');
            $table->tinyInteger('level_reached')->default(1);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index(['game_type', 'theme_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};

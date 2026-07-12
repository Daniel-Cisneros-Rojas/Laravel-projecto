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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id')->constrained()->onDelete('cascade');
            $table->string('hanzi')->comment('Carácter chino');
            $table->string('pinyin')->comment('Pronunciación romanizada');
            $table->text('meaning')->nullable()->comment('Significado en español');
            $table->tinyInteger('level')->default(1)->comment('Nivel de dificultad');
            $table->string('image')->nullable()->comment('Imagen descriptiva');
            $table->string('audio')->nullable()->comment('Archivo de pronunciación');
            $table->text('example_sentence')->nullable()->comment('Oración de ejemplo');
            $table->timestamps();

            $table->index(['theme_id', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};

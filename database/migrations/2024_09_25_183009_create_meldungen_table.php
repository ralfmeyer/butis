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
        Schema::create('meldungen', function (Blueprint $table) {
            $table->id(); // id-Feld automatisch als Primärschlüssel
            $table->unsignedBigInteger('mitarbeiter');
            $table->unsignedBigInteger('anmitarbeiter');
            $table->text('nachricht');
            $table->boolean('erledigt')->default(0); // tinyint(1) wird als boolean interpretiert
            $table->smallInteger('art');
            $table->unsignedBigInteger('zielid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meldungen');
    }
};

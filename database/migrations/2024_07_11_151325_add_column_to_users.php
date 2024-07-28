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
        Schema::table('users', function (Blueprint $table) {
            $table->string('personalnr')->nullable();
            $table->string('anrede')->nullable();
            $table->string('vorname')->nullable();
            $table->date('gebdatum')->nullable();
            $table->integer('stelle')->nullable();
            $table->unsignedTinyInteger('anstellung')->default(0);
            $table->string('besoldung')->nullable();
            $table->string('lregelbeurteilung')->nullable();
            $table->string('lsonstbeurteilung')->nullable();
            $table->boolean('ausgeschieden')->default(false);
            $table->string('berechtigung')->nullable();
            $table->date('nbeurteilung')->nullable();
            $table->string('amt')->nullable();
            $table->text('bemerkung')->nullable();
            $table->date('vertragsende')->nullable();
            $table->smallInteger('teilzeit')->nullable();
            $table->boolean('benachrichtigt')->default(false);
            $table->date('abgabedatum')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

        });
    }
};

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
        Schema::create('stellen', function (Blueprint $table) {
            $table->id();
            $table->string('kennzeichen');
            $table->string('bezeichnung');
            $table->integer('ebene');
            $table->integer('uebergeordnet');
            $table->boolean('fuehrungskompetenz')->default(false);
            $table->smallInteger('l')->nullable();
            $table->smallInteger('r')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stellen');
    }
};

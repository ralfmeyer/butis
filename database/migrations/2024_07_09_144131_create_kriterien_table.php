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
        Schema::create('kriterien', function (Blueprint $table) {
            $table->id();
            $table->string('bereich', 80);
            $table->string('nummer', 10);
            $table->string('ueberschrift', 80);
            $table->text('text1');
            $table->text('text2');
            $table->text('text3');
            $table->text('text4');
            $table->text('text5');
            $table->smallInteger('art');
            $table->text('hinweistextallgemein');
            $table->text('hinweistext1');
            $table->text('hinweistext2');
            $table->text('hinweistext3');
            $table->text('hinweistext4');
            $table->text('hinweistext5');
            $table->tinyInteger('fuehrungsmerkmal')->default(0)->comment('Schaltet die Option im Beurteilungsbogen an oder aus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriterien');
    }
};

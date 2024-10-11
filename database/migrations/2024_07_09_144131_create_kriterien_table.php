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
            $table->text('text1')->nullable();
            $table->text('text2')->nullable();
            $table->text('text3')->nullable();
            $table->text('text4')->nullable();
            $table->text('text5')->nullable();
            $table->smallInteger('art');
            $table->text('hinweistextallgemein')->nullable();
            $table->text('hinweistext1')->nullable();
            $table->text('hinweistext2')->nullable();
            $table->text('hinweistext3')->nullable();
            $table->text('hinweistext4')->nullable();
            $table->text('hinweistext5')->nullable();
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

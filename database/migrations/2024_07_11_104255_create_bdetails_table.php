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
        Schema::create('bdetails', function (Blueprint $table) {
            $table->id();
            $table->integer('beurteilungid');
            $table->integer('beurteilungsmerkmalid');
            $table->smallInteger('beurteiler1note')->nullable();
            $table->smallInteger('beurteiler2note')->nullable();
            $table->text('beurteiler1bemerkung')->nullable();
            $table->text('beurteiler2bemerkung')->nullable();
            $table->text('zusatz')->nullable();
            $table->dateTime('beurteiler1laenderung')->nullable();
            $table->dateTime('beurteiler2laenderung')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bdetails');
    }
};

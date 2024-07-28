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

            Schema::create('beurteilung', function (Blueprint $table) {
                $table->integer('id');
                $table->integer('mitarbeiterid');
                $table->tinyInteger('mitarbeiterfuehrung')->default(-1);
                $table->integer('beurteiler1');
                $table->integer('beurteiler2');
                $table->integer('stelleid');
                $table->string('stellebeurteiler1', 80)->nullable();
                $table->string('stellebeurteiler2', 80)->nullable();
                $table->string('stellebeurteilter', 80)->nullable();
                $table->date('datum');
                $table->date('abgabedatum');
                $table->text('bemerkung1')->nullable();
                $table->text('bemerkung2')->nullable();
                $table->smallInteger('gesamtnote1')->default(0);
                $table->smallInteger('gesamtnote2')->default(0);
                $table->text('gesamtnote1begruendung')->nullable();
                $table->text('gesamtnote2begruendung')->nullable();
                $table->tinyInteger('regelbeurteilung')->default(1)->comment('1 = Regel; 0 = Bedarf; 2 = Probezeit');
                $table->tinyInteger('beurteilungszeitpunkt')->default(-1)->comment('-1 = Nein, 0=zur Hälfte, 1=am Ende');
                $table->tinyInteger('abgeschlossen1')->default(0);
                $table->tinyInteger('abgeschlossen2')->default(0);
                $table->tinyInteger('veraltet')->default(0);
                $table->text('zusatz1')->nullable();
                $table->text('zusatz2')->nullable();
                $table->string('besoldung', 10);
                $table->date('zeitraumvon')->nullable();
                $table->date('zeitraumbis')->nullable();
                $table->text('aufgabenbereich')->nullable();
                $table->text('anlass')->nullable();
                $table->dateTime('ledit1')->nullable();
                $table->dateTime('ledit2')->nullable();
                $table->tinyInteger('nr_gesetzt')->nullable();
                $table->smallInteger('anstellung')->nullable();
                $table->smallInteger('teilzeit')->default(0);
                $table->string('amt', 5)->nullable();
                $table->tinyInteger('geeignet1')->default(-1)->comment('0 = geeignet; 1 = bedingt geeignet; 2 = ungeeignet');
                $table->tinyInteger('geeignet2')->default(-1)->comment('0 = geeignet; 1 = bedingt geeignet; 2 = ungeeignet');
                $table->integer('version')->default(1);
                $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beurteilung');
    }
};

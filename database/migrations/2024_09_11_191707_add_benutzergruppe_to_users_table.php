<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Fügt das Feld 'benutzergruppe' nach dem Feld 'updated_at' hinzu
            $table->integer('benutzergruppe')
                  ->default(0)
                  ->comment('0 User, 99 Admin')
                  ->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Entfernt das Feld 'benutzergruppe'
            $table->dropColumn('benutzergruppe');
        });
    }
};

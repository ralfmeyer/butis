<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meldung extends Model
{
    use HasFactory;

    protected $table = 'meldungen';

    // Fülle die Felder, die per Massenzuweisung gefüllt werden dürfen
    protected $fillable = [
        'mitarbeiter',
        'anmitarbeiter',
        'nachricht',
        'erledigt',
        'art',
        'zielid'
    ];
}

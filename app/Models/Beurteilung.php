<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bdetails;

class Beurteilung extends Model
{
    use HasFactory;

    protected $table = 'beurteilung';

    protected $fillable = [
        'id',
        'mitarbeiterid',
        'mitarbeiterfuehrung',
        'beurteiler1',
        'beurteiler2',
        'stelleid',
        'stellebeurteiler1',
        'stellebeurteiler2',
        'stellebeurteilter',
        'datum',
        'abgabedatum',
        'bemerkung1',
        'bemerkung2',
        'gesamtnote1',
        'gesamtnote2',
        'gesamtnote1begruendung',
        'gesamtnote2begruendung',
        'regelbeurteilung',
        'beurteilungszeitpunkt',
        'abgeschlossen1',
        'abgeschlossen2',
        'veraltet',
        'zusatz1',
        'zusatz2',
        'besoldung',
        'zeitraumvon',
        'zeitraumbis',
        'aufgabenbereich',
        'anlass',
        'ledit1',
        'ledit2',
        'nr_gesetzt',
        'anstellung',
        'teilzeit',
        'amt',
        'geeignet1',
        'geeignet2',
        'version'
    ];    

    public function bdetails()
    {
        return $this->hasMany(Bdetails::class, 'beurteilungid');
    }

}

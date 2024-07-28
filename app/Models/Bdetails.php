<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Beurteilung;

class Bdetails extends Model
{
    use HasFactory;

    protected $table = 'bdetails';
    protected $fillable = [
        'id',
        'beurteilungid',
        'beurteilungsmerkmalid',
        'beurteiler1note',
        'beurteiler2note',
        'beurteiler1bemerkung',
        'beurteiler2bemerkung',
        'zusatz',
        'beurteiler1laenderung',
        'beurteiler2laenderung',
    ];    

    public function beurteilung()
    {
        return $this->belongsTo(Beurteilung::class, 'beurteilungid');
    }    
}

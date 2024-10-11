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

    protected $attributes = [
        'id' => null,
        'beurteilungid' => -1,
        'beurteilungsmerkmalid' => -1,
        'beurteiler1note' => null,
        'beurteiler2note' => null,
        'beurteiler1bemerkung' => null,
        'beurteiler2bemerkung' => null,
        'zusatz' => null,
        'beurteiler1laenderung' => null,
        'beurteiler2laenderung' => null,
    ];

    public function __construct(){
        parent::__construct($this->attributes);
    }

    public function beurteilung()
    {
        return $this->belongsTo(Beurteilung::class, 'beurteilungid');
    }


}

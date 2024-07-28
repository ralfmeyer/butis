<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriterien extends Model
{
    use HasFactory;


    protected $table = 'kriterien';

    protected $fillable = [
        'id',
        'bereich',
        'nummer',
        'ueberschrift',
        'text1',
        'text2',
        'text3',
        'text4',
        'text5',
        'art',
        'hinweistextallgemein',
        'hinweistext1',
        'hinweistext2',
        'hinweistext3',
        'hinweistext4',
        'hinweistext5',
        'fuehrungsmerkmal',
    ];    

    public function stelleBezeichnung()
    {
        return $this->belongsTo(Stelle::class, 'stelle');
    }    

}
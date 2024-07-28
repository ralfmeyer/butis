<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stelle extends Model
{
    use HasFactory;

    protected $table = 'stellen';

    protected $fillable = [
        'id',        
        'kennzeichen',
        'bezeichnung',
        'ebene',
        'uebergeordnet',
        'fuehrungskompetenz',
        'l',
        'r',
    ];    


    public function uebergeordneteStelle()
    {
        return $this->belongsTo(Stelle::class, 'uebergeordnet');
    }

    
}

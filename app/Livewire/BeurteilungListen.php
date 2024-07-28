<?php

namespace App\Livewire;

use App\Models\Beurteilung;
use App\Models\Bdetails;
use Livewire\Component;
use Illuminate\Support\Facades\Session; // Session-Fassade hinzufügen
use Illuminate\Support\Facades\Log;

      
class BeurteilungListen extends Component
{
    
    
    public $beurteilungen;

    public $kopfueberschrift = "Beurteilungen";

    public function mount()
    {
        $this->beurteilungen = Beurteilung::all();
    }

    public function render()
    {
        return view('livewire.beurteilung.listen');
    }

}

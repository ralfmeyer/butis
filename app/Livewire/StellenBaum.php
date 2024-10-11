<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Stelle;
use Livewire\Attributes\On;


class StellenBaum extends Component
{


    public $stellen;

    public function mount($parent_id = -1)
    {
        $this->stellen = Stelle::with(['children', 'mitarbeiter'])->where('uebergeordnet', '=', $parent_id)->get();
    }

    public function render()
    {
        return view('livewire.stellen.baum', ['stellen' => $this->stellen]);
    }

    public function triggerEdit($id)
    {
        // Das Event auslösen, das von der übergeordneten Komponente gehört wird

        $this->dispatch('editStelle', $id);
    }

    #[On('refreshStelle')]
    public function refreshStelle($id){
        
        // Den Eintrag finden und aktualisieren
        $stelle = $this->stellen->where('id', $id)->first();

        if ($stelle) {
            $stelle->refresh(); // Daten neu laden
        }
    }

}

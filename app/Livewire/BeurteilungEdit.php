<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Beurteilung;

class BeurteilungEdit extends Component
{

    public $beurteilung;

    public function mount($id){
        $this->beurteilung = Beurteilung::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.beurteilung.edit');
    }

}

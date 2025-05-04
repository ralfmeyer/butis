<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class MitarbeiterAbgabe extends Component
{
    public $personalnr;
    public $datum;

    protected $rules = [
        'personalnr' => 'required|exists:users,personalnr',
        'datum' => 'required|date',
    ];

    /**
     * Handle an incoming authentication request.
     */
    public function update()
    {
        $this->validate();

        $user = User::where('personalnr', $this->personalnr)->first();

        if ($user) {
            $user->abgabedatum = ($this->datum === '') ? null: $this->datum;

            $user->save();

            session()->flash('status', 'Daten erfolgreich aktualisiert.');
        } else {
            session()->flash('status', 'Benutzer nicht gefunden.');
        }

    }

    public function mount($id = null){
        
        if ($id){
            $this->personalnr = $personalnr;
        }
        $this->datum = date('Y-m-d');
    }

    public function render()
    {

        return view('livewire.mitarbeiter.abgabe');
    }
}

<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Mitarbeiter;
use Illuminate\Support\Facades\Session; // Session-Fassade hinzufügen
use Illuminate\Support\Facades\Log;


class MitarbeiterListen extends Component
{
    
    public $id;
    public $personalnr;
    public $anrede;
    public $vorname;
    public $name;
    public $gebdatum;
    public $stelle;
    public $password;
    public $anstellung;
    public $besoldung;
    public $lregelbeurteilung;
    public $lsonstbeurteilung;
    public $ausgeschieden;
    public $berechtigung;
    public $nbeurteilung;
    public $amt;
    public $bemerkung;
    public $email;
    public $vertragsende;
    public $teilzeit;
    public $benachrichtigt;
    public $abgabedatum;


    public $isModified ;


    public $mitarbeiterliste;
    public $mitarbeiter;

    public $showForm = false ;

    public $ueberschrift = "Auflistung aller Mitarbeiter";


    public function mount(){
        if (env('PRIVATE')){
            $this->ueberschrift = "xxx";
        }
    }

    public function render()
    {
        // $this->stellen = Stelle::orderBy('uebergeordnet')->get();
        $this->mitarbeiterliste = Mitarbeiter::with('stelleBezeichnung')->get();
        //$this->stellen = Stelle::with('uebergeordneteStelle')->orderBy('uebergeordnet')->get();
        return view('livewire.mitarbeiter.listen');
    }


    public function save()
    {
        
        //$mitarbeiter->id = $this->id;
        $this->mitarbeiter->personalnr        = $this->personalnr;
        $this->mitarbeiter->anrede            = $this->anrede ;
        $this->mitarbeiter->vorname           = $this->vorname ;
        $this->mitarbeiter->name              = $this->name;
        $this->mitarbeiter->gebdatum          = $this->gebdatum;
        $this->mitarbeiter->stelle            = $this->stelle;
        $this->mitarbeiter->password          = $this->password;
        $this->mitarbeiter->anstellung        = $this->anstellung;
        $this->mitarbeiter->besoldung         = $this->besoldung ;
        $this->mitarbeiter->lregelbeurteilung = $this->lregelbeurteilung ;
        $this->mitarbeiter->lsonstbeurteilung = $this->lsonstbeurteilung;
        $this->mitarbeiter->ausgeschieden     = $this->ausgeschieden;
        $this->mitarbeiter->berechtigung      = $this->berechtigung;
        $this->mitarbeiter->nbeurteilung      = $this->nbeurteilung;        

        $this->mitarbeiter->amt               = $this->amt ;
        $this->mitarbeiter->bemerkung         = $this->bemerkung ;
        $this->mitarbeiter->email             = $this->email;
        $this->mitarbeiter->vertragsende      = $this->vertragsende;
        $this->mitarbeiter->teilzeit          = $this->teilzeit;
        $this->mitarbeiter->benachrichtigt    = $this->benachrichtigt;        
        $this->mitarbeiter->abgabedatum       = $this->abgabedatum;     


        $this->mitarbeiter->save();
        $this->isModified = false ;
        Log::info($this->mitarbeiter);
        $this->showForm = false;
        // $this->dispatch('doMessage', ['Änderungen gespeichert']);
         session()->flash('message', 'Änderungen gespeichert.');

    }  
    
    public function edit($id){
        $this->showForm = true;
        $this->id = $id;
        $this->mitarbeiter = Mitarbeiter::find($this->id);

        $this->id                = $this->mitarbeiter->id ;
        $this->personalnr        = $this->mitarbeiter->personalnr;
        $this->anrede            = $this->mitarbeiter->anrede ;
        $this->vorname           = $this->mitarbeiter->vorname ;
        $this->name              = $this->mitarbeiter->name;
        $this->gebdatum          = $this->mitarbeiter->gebdatum;
        $this->stelle            = $this->mitarbeiter->stelle;
        $this->password          = $this->mitarbeiter->password;
        $this->anstellung        = $this->mitarbeiter->anstellung;
        $this->besoldung         = $this->mitarbeiter->besoldung ;
        $this->lregelbeurteilung = $this->mitarbeiter->lregelbeurteilung ;
        $this->lsonstbeurteilung = $this->mitarbeiter->lsonstbeurteilung;
        $this->ausgeschieden     = $this->mitarbeiter->ausgeschieden;
        $this->berechtigung      = $this->mitarbeiter->berechtigung;
        $this->nbeurteilung      = $this->mitarbeiter->nbeurteilung;        
                                          
        $this->amt               = $this->mitarbeiter->amt ;
        $this->bemerkung         = $this->mitarbeiter->bemerkung ;
        $this->email             = $this->mitarbeiter->email;
        $this->vertragsende      = $this->mitarbeiter->vertragsende;
        $this->teilzeit          = $this->mitarbeiter->teilzeit;
        $this->benachrichtigt    = $this->mitarbeiter->benachrichtigt;        
        $this->abgabedatum       = $this->mitarbeiter->abgabedatum;   
        $this->isModified = false ;

    }
}

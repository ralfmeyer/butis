<?php

namespace App\Livewire;

use App\Models\Kriterien;
use Livewire\Component;
use Illuminate\Support\Facades\Session; // Session-Fassade hinzufügen
use Illuminate\Support\Facades\Log;


class KriterienListen extends Component
{
    
    
    public $id;
    public $bereich;
    public $nummer;
    public $ueberschrift;
    public $text1;
    public $text2;
    public $text3;
    public $text4;
    public $text5;
    public $art;
    public $hinweistextallgemein;
    public $hinweistext1;
    public $hinweistext2;
    public $hinweistext3;
    public $hinweistext4;
    public $hinweistext5;
    public $fuehrungsmerkmal;

    public $isModified ;


    public $kopfueberschrift;

    public $kriterien;
    public $kriterium;

    public $showForm = false ;

    public $header_ueberschrift = "Auflistung aller Kriterien";


    public function mount(){
        if (env('PRIVATE')){
            $this->kopfueberschrift = "xxx";
        }
    }

    public function render()
    {
        // $this->stellen = Stelle::orderBy('uebergeordnet')->get();
        $this->kriterien = Kriterien::get();
        return view('livewire.kriterien.listen');
    }


    public function save()
    {
        
        //$stelle->id = $this->id;
        $this->kriterium->bereich                = $this->bereich;
        $this->kriterium->nummer                 = $this->nummer ;
        $this->kriterium->ueberschrift           = $this->ueberschrift ;
        $this->kriterium->text1                  = $this->text1;
        $this->kriterium->text2                  = $this->text2;
        $this->kriterium->text3                  = $this->text3;
        $this->kriterium->text4                  = $this->text4;
        $this->kriterium->text5                  = $this->text5;
        $this->kriterium->art                    = $this->art;
        $this->kriterium->hinweistextallgemein   = $this->hinweistextallgemein;
        $this->kriterium->hinweistext1           = $this->hinweistext1;
        $this->kriterium->hinweistext2           = $this->hinweistext2;
        $this->kriterium->hinweistext3           = $this->hinweistext3;
        $this->kriterium->hinweistext4           = $this->hinweistext4;
        $this->kriterium->hinweistext5           = $this->hinweistext5;
        $this->kriterium->fuehrungsmerkmal       = $this->fuehrungsmerkmal;
        $this->kriterium->save();
        $this->isModified = false ;
        
        $this->showForm = false;
        // $this->dispatch('doMessage', ['Änderungen gespeichert']);
         session()->flash('message', 'Änderungen gespeichert.');

    }  
    
    public function edit($id){
        $this->showForm = true;
        $this->id = $id;
        $this->kriterium = Kriterien::find($this->id);

        $this->bereich                = $this->kriterium->bereich;
        $this->nummer                 = $this->kriterium->nummer ;
        $this->ueberschrift           = $this->kriterium->ueberschrift ;
        $this->text1                  = $this->kriterium->text1;
        $this->text2                  = $this->kriterium->text2;
        $this->text3                  = $this->kriterium->text3;
        $this->text4                  = $this->kriterium->text4;
        $this->text5                  = $this->kriterium->text5;
        $this->art                    = $this->kriterium->art;
        $this->hinweistextallgemein   = $this->kriterium->hinweistextallgemein;
        $this->hinweistext1           = $this->kriterium->hinweistext1;
        $this->hinweistext2           = $this->kriterium->hinweistext2;
        $this->hinweistext3           = $this->kriterium->hinweistext3;
        $this->hinweistext4           = $this->kriterium->hinweistext4;
        $this->hinweistext5           = $this->kriterium->hinweistext5;        
        $this->fuehrungsmerkmal           = $this->kriterium->fuehrungsmerkmal;       

        $this->isModified = false ;

    }
}

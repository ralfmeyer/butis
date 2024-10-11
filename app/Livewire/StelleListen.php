<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Stelle;
use Illuminate\Support\Facades\Session; // Session-Fassade hinzufügen
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;


class StelleListen extends Component
{

    public $id;
    public $kennzeichen;
    public $bezeichnung;
    public $ebene;
    public $uebergeordnet;
    public $uebergeordnetName;
    public $fuehrungskompetenz;
    public $l;
    public $r;

    public $isModified ;


    public $stellen;
    public $stelle;

    public $showForm = false ;

    public $ueberschrift = "Auflistung aller Stellen";

    public $zaehler = 0;

    public $dataList ;

    public $uebergeordnetIdAlt = 0 ;




    public function mount(){

        if (env('PRIVATE')){
            $this->ueberschrift = "xxx";
        }
        $this->zaehler = 0 ;
        $this->stellen = Stelle::with('children')->where('uebergeordnet', '=', -1)->get();
        if (is_null($this->stellen)){
            Log::error('StellenListen - Stellen ist null' , [ -1]);
        }
        else
        {
            Log::error('StellenListen - Stellen ist nicht null' , [ -1]);
        }
        $this->dataList = Stelle::select(['id', 'bezeichnung'])->where('fuehrungskompetenz', true)->get();
        //dd($this->stellen->first()->children[0]);
    }

    public function render()
    {


       // $this->stellen = Stelle::with('uebergeordneteStelle')->orderBy('bezeichnung')->get();



        return view('livewire.stellen.listen');
    }


    public function save()
    {

        //$stelle->id = $this->id;
        $this->stelle->kennzeichen = $this->kennzeichen;
        $this->stelle->bezeichnung = $this->bezeichnung ;
        $this->stelle->ebene = $this->ebene ;
        $this->stelle->uebergeordnet = $this->uebergeordnet;
        $this->stelle->fuehrungskompetenz = $this->fuehrungskompetenz;
        $this->stelle->l = $this->l;
        $this->stelle->r = $this->r;
        $this->stelle->save();
        $this->isModified = false ;
        Log::info($this->stelle);
        $this->showForm = false;
        // $this->dispatch('doMessage', ['Änderungen gespeichert']);
         session()->flash('message', 'Änderungen gespeichert.');

        if ( $this->uebergeordnetIdAlt === $this->stelle->uebergeordnet){
            $this->dispatch('refreshStelle', $this->stelle->id);
        }
        else{
            $this->dispatch('refreshPage');
        }

    }
    #[On('editStelle')]
    public function edit($id){


        $this->showForm = true;
        $this->id = $id;
        $this->stelle = Stelle::find($this->id);

        Log::info( 'Mount Stelle: ', [ $this->stelle ]);

        $this->id = $this->stelle->id ;
        $this->kennzeichen = $this->stelle->kennzeichen ;
        $this->bezeichnung = $this->stelle->bezeichnung;
        $this->ebene = $this->stelle->ebene;
        $this->uebergeordnet = $this->stelle->uebergeordnet;
        $this->uebergeordnetIdAlt = $this->stelle->uebergeordnet;

        $ueber = Stelle::find($this->stelle->uebergeordnet);
        if ($ueber) {
            $this->uebergeordnetName = $ueber->bezeichnung;
        }
        else
            $this->uebergeordnetName = 'Keine';

        $this->fuehrungskompetenz = $this->stelle->fuehrungskompetenz;
        $this->l = $this->stelle->l;
        $this->r = $this->stelle->r;
        $this->isModified = false ;



    }
}

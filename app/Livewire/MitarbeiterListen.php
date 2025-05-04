<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Mitarbeiter;
use App\Models\Stelle;
use App\Models\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MitarbeiterListen extends Component
{
    use WithPagination;

    const CONFIG_MITARBEITER_SORTFIELD = 'M_SORTFIELD';
    const CONFIG_MITARBEITER_SORTDIRECTION = 'M_SORTDIRECTION';
    const CONFIG_MITARBEITER_GESPERRT = 'M_GESPERRT';



    public $mitarbeiter;
    public $showForm = false;
    public $showAbgabeForm = false;
    public $ueberschrift = "Auflistung aller Mitarbeiter";

    public $nameFilter;
    public $stelleFilter;
    public $personalnrFilter;

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

    private $mitarbeiterliste;
	public $dataList ;
    public $isModified;

    public $anstellungTypes;
    public $sortField = "name";
    public $sortDirection = "asc";
    public $filterGesperrt = false ; // Gespert = false = Gesperrt true => gesperrte anzeigen


    public function mount()
    {
        if (env('PRIVATE')) {
            $this->ueberschrift = "xxx";
        }
        $this->anstellungTypes = Mitarbeiter::$anstellungTypes;

        $this->sortField = Config::personalnrStringDefault(self::CONFIG_MITARBEITER_SORTFIELD, 'name');
        $this->sortDirection = Config::personalnrStringDefault(self::CONFIG_MITARBEITER_SORTDIRECTION, 'asc');
        $this->filterGesperrt = (Config::personalnrStringDefault(self::CONFIG_MITARBEITER_GESPERRT, 'false') === 'false' ) ? false : true ;
    }

    public function updateQuery(){



        $qry = Mitarbeiter::query();
        if ($this->nameFilter) {
            $qry->where(function($query) {
                $query->where('name', 'like', '%' . $this->nameFilter . '%')
                    ->orWhere('vorname', 'like', '%' . $this->nameFilter . '%');
            });
        }

        if ($this->stelleFilter) {
            $qry->whereHas('stelleBezeichnung', function ($query) {
                $query->where('bezeichnung', 'like', '%' . $this->stelleFilter . '%');
            });
        }

        if ($this->personalnrFilter) {
            $qry->where('personalnr', 'like', '%' . $this->personalnrFilter . '%');
        }

        if ($this->filterGesperrt === false){
            $qry->where('ausgeschieden', false);
        }


        $this->mitarbeiterliste = $qry->orderBy($this->sortField, $this->sortDirection )->paginate(30);
        $this->dataList = Stelle::select(['id', 'bezeichnung'])->orderBy('bezeichnung')->get()->toArray();

        $a = [ 'id' => -1, 'bezeichnung' => 'Keine'];
        array_unshift($this->dataList, $a);
       // dd($this->dataList);
    }

    public function render()
    {
        $this->updateQuery();

        return view('livewire.mitarbeiter.listen', ['mitarbeiterliste' => $this->mitarbeiterliste]);
    }


    public function updated($field)
    {
        $this->isModified = true ;
        Log::info('Updated', [$field]);
    }


    public function neu()
    {
        $this->resetFormData();
        $this->showForm = true;
        $this->isModified = false ;
    }

    private function checkDate($dateField){

        //dd($dateField);
        return ( !Carbon::parse($dateField) || $dateField === '' || $dateField === '0000-00-00' || empty($dateField)) ? null : Carbon::parse($dateField)->format('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'personalnr' => 'required|string|max:255',
            'vorname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            //'email' => 'required|email|unique:users,email,' . ($this->mitarbeiter->id ?? 'NULL'),
            'stelle' => 'required',
            'password' => 'nullable|min:6',
            'anstellung' => 'required|integer|min:0|max:8',
        ]);

        if ($this->mitarbeiter) {
            $mitarbeiter = $this->mitarbeiter;
        } else {
            $mitarbeiter = new Mitarbeiter();
        }

        $mitarbeiter->personalnr = $this->personalnr;
        $mitarbeiter->anrede = $this->anrede;
        $mitarbeiter->vorname = $this->vorname;
        $mitarbeiter->name = $this->name;

        $mitarbeiter->gebdatum = $this->checkDate($this->gebdatum);
        //dd($mitarbeiter->gebdatum);
        $mitarbeiter->stelle = $this->stelle;
        $mitarbeiter->anstellung = $this->anstellung;
        $mitarbeiter->besoldung = $this->besoldung;
        $mitarbeiter->lregelbeurteilung = $this->checkDate($this->lregelbeurteilung);
        $mitarbeiter->lsonstbeurteilung = $this->checkDate($this->lsonstbeurteilung);

        $mitarbeiter->ausgeschieden = ($this->ausgeschieden === false || $this->ausgeschieden === 0 ) ? 0 : 1;
        $mitarbeiter->berechtigung = $this->berechtigung;
        $mitarbeiter->nbeurteilung = $this->checkDate($this->nbeurteilung);
        $mitarbeiter->amt = $this->amt;
        $mitarbeiter->bemerkung = $this->bemerkung;
        $mitarbeiter->email = $this->email;
        $mitarbeiter->vertragsende = $this->checkDate($this->vertragsende);
        $mitarbeiter->teilzeit =  ($this->teilzeit === false || $this->teilzeit === 0 ) ? 0 : 1;
        $mitarbeiter->benachrichtigt = ($this->benachrichtigt === false || $this->benachrichtigt === 0 ) ? 0 : 1;

        $mitarbeiter->abgabedatum = $this->checkDate($this->abgabedatum);

        if ($this->password) {

            $mitarbeiter->password = Hash::make($this->password);
        }
        //dd($mitarbeiter->gebdatum);
        $mitarbeiter->save();

        $this->showForm = false;
        $this->isModified = false ;

        session()->flash('message', 'Änderungen gespeichert.');
    }

    public function edit($id)
    {
        $this->mitarbeiter = Mitarbeiter::findOrFail($id);
        $this->fillFormData($this->mitarbeiter);
        $this->showForm = true;
        $this->isModified = false;
    }

    public function editAbgabe($id)
    {

        $this->mitarbeiter = Mitarbeiter::findOrFail($id);
        $this->id = $id;
        $this->vorname = $this->mitarbeiter->vorname;
        $this->name = $this->mitarbeiter->name;
        $this->abgabedatum = date('Y-m-d');
        $this->showAbgabeForm = true;
        $this->isModified = false;
    }

    public function saveAbgabe()
    {
        $this->mitarbeiter = Mitarbeiter::findOrFail($this->id);
        $this->mitarbeiter->abgabedatum = ($this->abgabedatum === '') ? null: $this->abgabedatum;
        $this->mitarbeiter->save();
        $this->showAbgabeForm = false;
        $this->isModified = false;
    }


    private function resetFormData()
    {
        $this->personalnr = '';
        $this->anrede = '';
        $this->vorname = '';
        $this->name = '';
        $this->gebdatum = '';
        $this->stelle = '';
        $this->password = '';
        $this->anstellung = '';
        $this->besoldung = '';
        $this->lregelbeurteilung = '';
        $this->lsonstbeurteilung = '';
        $this->ausgeschieden = '';
        $this->berechtigung = 5;
        $this->nbeurteilung = '';
        $this->amt = '';
        $this->bemerkung = '';
        $this->email = '';
        $this->vertragsende = '';
        $this->teilzeit = '';
        $this->benachrichtigt = '';
        $this->abgabedatum = '';
    }

    private function fillFormData($mitarbeiter)
    {
        $this->personalnr = $mitarbeiter->personalnr;
        $this->anrede = $mitarbeiter->anrede;
        $this->vorname = $mitarbeiter->vorname;
        $this->name = $mitarbeiter->name;

        $this->gebdatum = $mitarbeiter->gebdatum;
        $this->stelle = $mitarbeiter->stelle;
        $this->password = '';
        $this->anstellung = $mitarbeiter->anstellung;
        $this->besoldung = $mitarbeiter->besoldung;
        $this->lregelbeurteilung = $mitarbeiter->lregelbeurteilung;
        $this->lsonstbeurteilung = $mitarbeiter->lsonstbeurteilung;
        $this->ausgeschieden = $mitarbeiter->ausgeschieden;
        $this->berechtigung = $mitarbeiter->berechtigung;
        $this->nbeurteilung = $mitarbeiter->nbeurteilung;
        $this->amt = $mitarbeiter->amt;
        $this->bemerkung = $mitarbeiter->bemerkung;
        $this->email = $mitarbeiter->email;
        $this->vertragsende = $mitarbeiter->vertragsende;
        $this->teilzeit = $mitarbeiter->teilzeit;
        $this->benachrichtigt = $mitarbeiter->benachrichtigt;

        $this->abgabedatum = $mitarbeiter->abgabedatum;



    }

    public function sort($fldname){

        if ($this->sortField === $fldname) {
            // Wenn die gleiche Spalte erneut ausgewählt wird, Richtung umkehren
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Neue Spalte auswählen und Standardrichtung setzen
            $this->sortField = $fldname;
            $this->sortDirection = 'asc';
        }

        Config::setPersonalNrString(self::CONFIG_MITARBEITER_SORTFIELD, $fldname);
        Config::setPersonalNrString(self::CONFIG_MITARBEITER_SORTDIRECTION, 'asc');

    }

    public function updatedFilterGesperrt($value){
        Log::info('Gesperrt ',[$value]);
        Config::setPersonalNrString(self::CONFIG_MITARBEITER_GESPERRT, $value);

    }

}

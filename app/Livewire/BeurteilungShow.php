<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Beurteilung;
use App\Models\Mitarbeiter;
use App\Models\Stelle;
use App\Models\Kriterien;
use App\Models\Bdetails;


use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class BeurteilungShow extends Component
{
    public $beurteilung;
    public $mitarbeiterid;
    public $mitarbeiterfuehrung;
    public $beurteiler1;
    public $beurteiler2;
    public $stelleid;
    public $stellebeurteiler1;
    public $stellebeurteiler2;
    public $stellebeurteilter;
    public $datum;
    public $abgabedatum;
    public $bemerkung1;
    public $bemerkung2;
    public $gesamtnote1;
    public $gesamtnote2;
    public $gesamtnote1begruendung;
    public $gesamtnote2begruendung;
    public $regelbeurteilung;
    public $beurteilungszeitpunkt;
    public $abgeschlossen1;
    public $abgeschlossen2;
    public $veraltet;
    public $zusatz1;
    public $zusatz2;
    public $besoldung;
    public $zeitraumvon;
    public $zeitraumbis;
    public $aufgabenbereich;
    public $anlass;
    public $ledit1;
    public $ledit2;
    public $nr_gesetzt;
    public $anstellung;
    public $teilzeit;
    public $amt;
    public $geeignet1;
    public $geeignet2;
    public $version;

    public $mitarbeiter;
    public $bearbeiter1;
    public $bearbeiter2;

    public $beurteiler1aktiv;
    public $beurteiler2aktiv;

    public $stelleB1;
    public $stelleB2;

    public $kriterien;


    public $grenze;

    public $bdetails;
    public $details;


    public function mount($id)
    {

        $beurteilung = Beurteilung::findOrFail($id);

        $this->beurteilung = $beurteilung;
        $this->mitarbeiterid = $beurteilung->mitarbeiterid;
        $this->mitarbeiterfuehrung = $beurteilung->mitarbeiterfuehrung;
        $this->beurteiler1 = $beurteilung->beurteiler1;
        $this->beurteiler2 = $beurteilung->beurteiler2;
        $this->stelleid = $beurteilung->stelleid;
        $this->stellebeurteiler1 = $beurteilung->stellebeurteiler1;
        $this->stellebeurteiler2 = $beurteilung->stellebeurteiler2;
        $this->stellebeurteilter = $beurteilung->stellebeurteilter;
        $this->datum = $beurteilung->datum;
        $this->abgabedatum = $beurteilung->abgabedatum;
        $this->bemerkung1 = $beurteilung->bemerkung1;
        $this->bemerkung2 = $beurteilung->bemerkung2;
        $this->gesamtnote1 = $beurteilung->gesamtnote1;
        $this->gesamtnote2 = $beurteilung->gesamtnote2;
        $this->gesamtnote1begruendung = $beurteilung->gesamtnote1begruendung;
        $this->gesamtnote2begruendung = $beurteilung->gesamtnote2begruendung;
        $this->regelbeurteilung = $beurteilung->regelbeurteilung;
        $this->beurteilungszeitpunkt = $beurteilung->beurteilungszeitpunkt;
        $this->abgeschlossen1 = $beurteilung->abgeschlossen1;
        $this->abgeschlossen2 = $beurteilung->abgeschlossen2;
        $this->veraltet = $beurteilung->veraltet;
        $this->zusatz1 = $beurteilung->zusatz1;
        $this->zusatz2 = $beurteilung->zusatz2;
        $this->besoldung = $beurteilung->besoldung;
        $this->zeitraumvon = $beurteilung->zeitraumvon;
        $this->zeitraumbis = $beurteilung->zeitraumbis;
        $this->aufgabenbereich = $beurteilung->aufgabenbereich;
        $this->anlass = $beurteilung->anlass;
        $this->ledit1 = $beurteilung->ledit1;
        $this->ledit2 = $beurteilung->ledit2;
        $this->nr_gesetzt = $beurteilung->nr_gesetzt;
        $this->anstellung = $beurteilung->anstellung;
        $this->teilzeit = $beurteilung->teilzeit;
        $this->amt = $beurteilung->amt;
        $this->geeignet1 = $beurteilung->geeignet1;
        $this->geeignet2 = $beurteilung->geeignet2;
        $this->version = $beurteilung->version;


        $this->mitarbeiter = Mitarbeiter::find($this->mitarbeiterid);
        $this->beurteiler1 = Mitarbeiter::find($this->beurteiler1);

        $this->beurteiler2 = Mitarbeiter::find($this->beurteiler2);


        $this->beurteiler1aktiv = true ;
        $this->beurteiler2aktiv = false ;

        $this->stelleB1 = Stelle::find($this->beurteiler1->stelle);
        $this->stelleB2 = Stelle::find($this->beurteiler2->stelle);


        $this->grenze = \DateTime::createFromFormat('Y-m-d', Env('GRENZE'));

        if ($beurteilung->datum >= $this->grenze) {
            $this->version = 2;
        }
        else
            $this->version = 1;

        if ($this->version == 2)
            $this->kriterien = Kriterien::where('art', 10 )->orderBy('nummer')->get();
        else
            $this->kriterien = Kriterien::where('art', 0 )->orderBy('nummer')->get();

        $this->details = [];
        foreach ($this->kriterien as $kriterium){


            $detail = Bdetails::where('beurteilungid', $beurteilung->id)
                    ->where('beurteilungsmerkmalid', $kriterium->id)
                    ->first();
            //dd($detail);
            /*

            echo "<pre>";
            var_dump($detail);
            echo "</pre>";

            */

            if ($detail){
                $this->details[$kriterium->id] = [
                    'k' => $kriterium,
                    'w' => [
                        'id' => $detail->id,
                        'beurteilungid' => $beurteilung->id,
                        'beurteilungsmerkmalid' => $kriterium->id,
                        'beurteiler1note' =>  $detail->beurteiler1note,
                        'beurteiler2note' =>  $detail->beurteiler2note,
                        'beurteiler1bemerkung' =>  $detail->beurteiler1bemerkung,
                        'beurteiler2bemerkung' =>  $detail->beurteiler2bemerkung,
                        'zusatz' => $detail->zusatz,
                        'beurteiler1laenderung' => $detail->beurteiler1laenderung,
                        'beurteiler2laenderung' => $detail->beurteiler2laenderung,
                    ]
                ];
            }
            else{
                $this->details[$kriterium->id] = [
                    'k' => $kriterium,
                    'w' => [
                        'id' => !isNull($detail) ? $detail->id : -1,
                        'beurteilungid' => $beurteilung->id,
                        'beurteilungsmerkmalid' => $kriterium->id,
                        'beurteiler1note' => 'nicht benotet',
                        'beurteiler2note' => -1,
                        'beurteiler1bemerkung' => '',
                        'beurteiler2bemerkung' => '',
                        'zusatz' => '',
                        'beurteiler1laenderung' => -1,
                        'beurteiler2laenderung' => -1,
                    ]
                ];
            }



        }

    }


    public function render()
    {
        return view('livewire.beurteilung.show');
    }

}

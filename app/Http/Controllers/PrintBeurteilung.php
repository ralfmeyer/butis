<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Beurteilung;
use App\Models\Mitarbeiter;
use App\Models\Stelle;
use App\Models\Kriterien;
use App\Models\Bdetails;

class PrintBeurteilung extends Controller
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

    public $selectedBId;

    public $zeigeMessage ;

    public $userId;


    public function showPrintPage(Request $request)
    {
        if (!session()->has('allow_print')) {
            abort(403, 'Drucken nicht erlaubt');
        }

        $this->beurteilung = session()->get('print_data');
        $this->fillBerurteilung();

        session()->forget(['allow_print', 'print_data']);

        $data = $this->getBeurteilungsDaten();

        return view('printbeurteilung', compact('data'));
    }

    private function fillBerurteilung(){
        $this->selectedBId = -1;



        $this->mitarbeiterid = $this->beurteilung->mitarbeiterid;
        $this->mitarbeiterfuehrung = $this->beurteilung->mitarbeiterfuehrung;
        $this->beurteiler1 = $this->beurteilung->beurteiler1;
        $this->beurteiler2 = $this->beurteilung->beurteiler2;
        $this->stelleid = $this->beurteilung->stelleid;
        $this->stellebeurteiler1 = $this->beurteilung->stellebeurteiler1;
        $this->stellebeurteiler2 = $this->beurteilung->stellebeurteiler2;
        $this->stellebeurteilter = $this->beurteilung->stellebeurteilter;
        $this->datum = $this->beurteilung->datum;
        $this->abgabedatum = $this->beurteilung->abgabedatum;
        $this->bemerkung1 = $this->beurteilung->bemerkung1;
        $this->bemerkung2 = $this->beurteilung->bemerkung2;
        $this->gesamtnote1 = $this->beurteilung->gesamtnote1;
        $this->gesamtnote2 = $this->beurteilung->gesamtnote2;
        $this->gesamtnote1begruendung = $this->beurteilung->gesamtnote1begruendung;
        $this->gesamtnote2begruendung = $this->beurteilung->gesamtnote2begruendung;
        $this->regelbeurteilung = $this->beurteilung->regelbeurteilung;
        $this->beurteilungszeitpunkt = $this->beurteilung->beurteilungszeitpunkt;
        $this->abgeschlossen1 = $this->beurteilung->abgeschlossen1;
        $this->abgeschlossen2 = $this->beurteilung->abgeschlossen2;
        $this->veraltet = $this->beurteilung->veraltet;
        $this->zusatz1 = $this->beurteilung->zusatz1;
        $this->zusatz2 = $this->beurteilung->zusatz2;
        $this->besoldung = $this->beurteilung->besoldung;
        $this->zeitraumvon = $this->beurteilung->zeitraumvon;
        $this->zeitraumbis = $this->beurteilung->zeitraumbis;
        $this->aufgabenbereich = $this->beurteilung->aufgabenbereich;
        $this->anlass = $this->beurteilung->anlass;
        $this->ledit1 = $this->beurteilung->ledit1;
        $this->ledit2 = $this->beurteilung->ledit2;
        $this->nr_gesetzt = $this->beurteilung->nr_gesetzt;
        $this->anstellung = $this->beurteilung->anstellung;
        $this->teilzeit = $this->beurteilung->teilzeit;
        $this->amt = $this->beurteilung->amt;
        $this->geeignet1 = $this->beurteilung->geeignet1;
        $this->geeignet2 = $this->beurteilung->geeignet2;
        $this->version = $this->beurteilung->version;


        $this->mitarbeiter = Mitarbeiter::find($this->mitarbeiterid);
        $this->beurteiler1 = Mitarbeiter::find($this->beurteiler1);

        $this->beurteiler2 = Mitarbeiter::find($this->beurteiler2);


        $this->beurteiler1aktiv = $this->beurteiler1->id === Auth::id();

        $this->beurteiler2aktiv = $this->beurteiler2->id === Auth::id();



        $this->stelleB1 = Stelle::find($this->beurteiler1->stelle);
        $this->stelleB2 = Stelle::find($this->beurteiler2->stelle);


        $this->grenze = \DateTime::createFromFormat('Y-m-d', Env('GRENZE'));
        $this->version = $this->beurteilung->version;
        /*
        if ($this->beurteilung->datum >= $this->grenze) {

        }
        else
            $this->version = 1;
        */

        if ($this->version == 2){
            $this->kriterien = Kriterien::where('art', 10 )->orderBy('nummer')->get();
            Log::info('Version 2');
        }
        else
        {
            $this->kriterien = Kriterien::where('art', 0 )->orderBy('nummer')->get();
            Log::info('Version 1');
        }

        $this->details = [];
        foreach ($this->kriterien as $kriterium){


            $detail = Bdetails::where('beurteilungid', $this->beurteilung->id)
                    ->where('beurteilungsmerkmalid', $kriterium->id)
                    ->first();

            if ($detail){
                $this->details[$kriterium->id] = [
                    'k' => $kriterium,
                    'w' => [
                        'id' => $detail->id,
                        'beurteilungid' => $this->beurteilung->id,
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
                        'id' => !empty($detail) ? $detail->id : -1,
                        'beurteilungid' => $this->beurteilung->id,
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


    private function getBeurteilungsDaten()
    {
        return [

            'selectedBId' => $this->beurteilung->id,
            'mitarbeiterid' => $this->beurteilung->mitarbeiterid,
            'mitarbeiterfuehrung' => $this->beurteilung->mitarbeiterfuehrung,
            'beurteiler1' => $this->beurteilung->beurteiler1,
            'beurteiler2' => $this->beurteilung->beurteiler2,
            'stelleid' => $this->beurteilung->stelleid,
            'stellebeurteiler1' => $this->beurteilung->stellebeurteiler1,
            'stellebeurteiler2' => $this->beurteilung->stellebeurteiler2,
            'stellebeurteilter' => $this->beurteilung->stellebeurteilter,
            'datum' => $this->beurteilung->datum,
            'abgabedatum' => $this->beurteilung->abgabedatum,
            'bemerkung1' => $this->beurteilung->bemerkung1,
            'bemerkung2' => $this->beurteilung->bemerkung2,
            'gesamtnote1' => $this->beurteilung->gesamtnote1,
            'gesamtnote2' => $this->beurteilung->gesamtnote2,
            'gesamtnote1begruendung' => $this->beurteilung->gesamtnote1begruendung,
            'gesamtnote2begruendung' => $this->beurteilung->gesamtnote2begruendung,
            'regelbeurteilung' => $this->beurteilung->regelbeurteilung,
            'beurteilungszeitpunkt' => $this->beurteilung->beurteilungszeitpunkt,
            'abgeschlossen1' => $this->beurteilung->abgeschlossen1,
            'abgeschlossen2' => $this->beurteilung->abgeschlossen2,
            'veraltet' => $this->beurteilung->veraltet,
            'zusatz1' => $this->beurteilung->zusatz1,
            'zusatz2' => $this->beurteilung->zusatz2,
            'besoldung' => $this->beurteilung->besoldung,
            'zeitraumvon' => $this->beurteilung->zeitraumvon,
            'zeitraumbis' => $this->beurteilung->zeitraumbis,
            'aufgabenbereich' => $this->beurteilung->aufgabenbereich,
            'anlass' => $this->beurteilung->anlass,
            'ledit1' => $this->beurteilung->ledit1,
            'ledit2' => $this->beurteilung->ledit2,
            'nr_gesetzt' => $this->beurteilung->nr_gesetzt,
            'anstellung' => $this->beurteilung->anstellung,
            'teilzeit' => $this->beurteilung->teilzeit,
            'amt' => $this->beurteilung->amt,
            'geeignet1' => $this->beurteilung->geeignet1,
            'geeignet2' => $this->beurteilung->geeignet2,
            'version' => $this->beurteilung->version,
            'mitarbeiter' => Mitarbeiter::find($this->beurteilung->mitarbeiterid),
            'beurteiler1' => Mitarbeiter::find($this->beurteilung->beurteiler1),
            'beurteiler2' => Mitarbeiter::find($this->beurteilung->beurteiler2),
            'beurteiler1aktiv' => Mitarbeiter::find($this->beurteilung->beurteiler1)->id === Auth::id(),
            'beurteiler2aktiv' => Mitarbeiter::find($this->beurteilung->beurteiler2)->id === Auth::id(),
            'stelleB1' => Stelle::find(Mitarbeiter::find($this->beurteilung->beurteiler1)->stelle),
            'stelleB2' => Stelle::find(Mitarbeiter::find($this->beurteilung->beurteiler2)->stelle),
            'grenze' => \DateTime::createFromFormat('Y-m-d', env('GRENZE')),
            'kriterien' => ($this->beurteilung->version == 2)
                ? Kriterien::where('art', 10)->orderBy('nummer')->get()
                : Kriterien::where('art', 0)->orderBy('nummer')->get(),
            'details' => $this->getBeurteilungsDetails(),
        ];
    }


    private function getBeurteilungsDetails()
    {
        $details = [];
        $kriterien = ($this->beurteilung->version == 2)
            ? Kriterien::where('art', 10)->orderBy('nummer')->get()
            : Kriterien::where('art', 0)->orderBy('nummer')->get();

        foreach ($kriterien as $kriterium) {
            $detail = Bdetails::where('beurteilungid', $this->beurteilung->id)
                ->where('beurteilungsmerkmalid', $kriterium->id)
                ->first();

            $details[$kriterium->id] = [
                'k' => $kriterium,
                'w' => [
                    'id' => optional($detail)->id ?? -1,
                    'beurteilungid' => $this->beurteilung->id,
                    'beurteilungsmerkmalid' => $kriterium->id,
                    'beurteiler1note' => optional($detail)->beurteiler1note ?? 'nicht benotet',
                    'beurteiler2note' => optional($detail)->beurteiler2note ?? -1,
                    'beurteiler1bemerkung' => optional($detail)->beurteiler1bemerkung ?? '',
                    'beurteiler2bemerkung' => optional($detail)->beurteiler2bemerkung ?? '',
                    'zusatz' => optional($detail)->zusatz ?? '',
                    'beurteiler1laenderung' => optional($detail)->beurteiler1laenderung ?? -1,
                    'beurteiler2laenderung' => optional($detail)->beurteiler2laenderung ?? -1,
                ]
            ];
        }

        return $details;
    }

}

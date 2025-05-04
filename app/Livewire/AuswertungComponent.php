<?php


namespace App\Livewire;

use Livewire\Component;
use App\Models\Mitarbeiter;
use App\Models\Beurteilung;
use App\Models\Kriterien;
use App\Models\Bdetails;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * @method \Livewire\Component layout(string $layout)
 */

class AuswertungComponent extends Component
{

    public $title = 'Auswertungskriterien festlegen';

    public $kopfueberschrift = 'Auswertungskriterien festlegen';

    public $sRegelbeurteilung = '-1';
    public $sBeurteilungszeitpunkt = '-99';
    public $sGeschlecht = 'all';
    public $sTeilzeit = '-1';
    public $sFuehrungskompetenz = '-1';

    public $startDate = '2015-01-01';
    public $endDate = '2024-12-31';

    public $sEbenen = [];
    public $sStellen = [];
    public $sBeurteiler = [];
    public $sAnstellungsart = [];
    public $sBesoldung = [];
    public $sBeurteilter = [];

    public $positions = [];
    public $selectedPositions = [];

    public $nurAktuelle = false;
    public $selectedEbenen = [ -1 ];
    public $selectedStellen = [ -1 ];
    public $selectedBeurteiler = [ -1 ];
    public $selectedAnstellungsarten = [ -1 ];
    public $selectedBesoldungen = [ -1 ];
    public $selectedBeurteilter = [ -1 ];

    public $sql = '';

    public $beurteilungen;

    private $header = "Name;Vorname;Stelle;Amt;Besoldung;Anstellung;Gesamtnote";


    public function mount() {
        $userId = Auth::id();

        /* Ebenen ************************************************************/
        $ebenen = Mitarbeiter::getEbenenUnterMitarbeiter($userId);
        $this->sEbenen = [
            ['id' => -1, 'name' => 'Alle'],
        ];
        foreach ($ebenen as $key => $ebene){
            $this->sEbenen[] = [ 'id' => $ebene, 'name' => $ebene];
        }

        /* Stellen ***********************************************************/
        $stellen = Mitarbeiter::getStellenUnterMitarbeiter($userId);

        $this->sStellen = [
            ['id' => -1, 'name' => 'Alle'],
        ];
        foreach ($stellen as $key => $stelle){
            $this->sStellen[] = [ 'id' => $stelle->id, 'name' => $stelle->bezeichnung];
        }

        /* Beurteiler ********************************************************/
        $beurteiler = Mitarbeiter::getBeurteilerUnterMitarbeiter($userId);

        $this->sBeurteiler = [
            ['id' => -1, 'name' => 'Alle'],
        ];

        foreach ($beurteiler as $key => $einBeurteiler){
            if ($einBeurteiler->id != $userId)
            $this->sBeurteiler[] = [ 'id' => $einBeurteiler->id, 'name' => $einBeurteiler->name];
        }

        /* Anstellung ********************************************************/
        $anstellung = Mitarbeiter::getAnstellungUnterMitarbeiter($userId);
        $this->sAnstellungsart = [
            ['id' => -1, 'name' => 'Alle'],
        ];
        foreach ($anstellung as $key => $eineAnstellung){
            $this->sAnstellungsart[] = [ 'id' => $eineAnstellung->anstellung, 'name' =>  Mitarbeiter::getAnstellung($eineAnstellung->anstellung)];
        }
        usort($this->sAnstellungsart, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });


        /* Besoldung *********************************************************/
        $besoldung = Mitarbeiter::getBesoldungUnterMitarbeiter($userId);
        $this->sBesoldung = [
            ['id' => -1, 'name' => 'Alle'],
        ];
        foreach ($besoldung as $key => $einebesoldung){
            $this->sBesoldung[] = [ 'id' => $einebesoldung->besoldung, 'name' =>  $einebesoldung->besoldung];
        }

        /* Beurteilter *******************************************************/
        $beurteilter = Mitarbeiter::getBeurteilterUnterMitarbeiter($userId);

        $this->sBeurteilter = [
            ['id' => -1, 'name' => 'Alle'],
        ];
        foreach ($beurteilter as $key => $einBeurteilter){
            if ($einBeurteilter->id != $userId)
                $this->sBeurteilter[] = [ 'id' => $einBeurteilter->id, 'name' =>  $einBeurteilter->name];
        }


    }

    public function applyFilters()
    {
        $header = 'Name;Vorname;Stelle;Amt;Besoldung;Anstellung;Gesamtnote;';

        $this->beurteilungen = $this->buildQuery();

        session()->flash('message', 'Filter angewendet!');
    }

    public function exportData()
    {

        session()->flash('message', 'Daten exportiert!');
    }

    public function render()
    {

        return view('livewire.auswertung-component')->layout('layouts.app');
    }

    public function buildQuery(){

        $query = Beurteilung::query()->select()
        ->with(['mitarbeiter']);

        $arr = Mitarbeiter::getMitarbeiterUnterMitarbeiter( Auth::id() );
        $arr = array_filter($arr, function($value) {
            return $value !== Auth::id(); // Behalte alle Werte außer 9
        });

        $query->whereIn('beurteilung.mitarbeiterid', $arr);
        $query->where('abgeschlossen2', true);

        // Nur aktuelle Beurteilungen
        if ($this->nurAktuelle) {
            $query->where('veraltet', 0);
        }

        // Regelbeurteilung Filter
        if ($this->sRegelbeurteilung != -1) {
            $query->where('regelbeurteilung', $this->sRegelbeurteilung);
        }

        // Beurteilungszeitpunkt Filter
        if ($this->sBeurteilungszeitpunkt != -99) {
            $query->where('beurteilungszeitpunkt', $this->sBeurteilungszeitpunkt);
        }

        // Geschlecht Filter
        if ($this->sGeschlecht != 'all') {
            $query->whereHas('mitarbeiter', function ($q) {
                $q->where('anrede', $this->sGeschlecht);
            });
        }

        // Teilzeit Filter
        if ($this->sTeilzeit != -1) {
            $query->whereHas('mitarbeiter', function ($q) {
                $q->where('teilzeit', $this->sTeilzeit);
            });
        }

        // Datumsbereich Filter
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('datum', [$this->startDate, $this->endDate]);
        }

        // Führungskompetenz Filter
        if ($this->sFuehrungskompetenz != -1) {
            $query->whereHas('mitarbeiter.stelle', function ($q) {
                $q->where('fuehrungskompetenz', $this->sFuehrungskompetenz);
            });
        }

        // Ebenen Filter
        if (!in_array(-1, $this->selectedEbenen)) {
            $query->whereHas('mitarbeiter.stelle', function ($q) {
                $q->whereIn('ebene', $this->selectedEbenen);
            });
        }

        // Stellen Filter
        if (!in_array(-1, $this->selectedStellen)) {
            $query->whereHas('mitarbeiter.stelle', function ($q) {
                $q->whereIn('id', $this->selectedStellen);
            });
        }

        // Beurteiler Filter
        if (!in_array(-1, $this->selectedBeurteiler)) {
            $query->where(function ($q) {
                $q->whereIn('beurteiler1', $this->selectedBeurteiler)
                ->orWhereIn('beurteiler2', $this->selectedBeurteiler);
            });
        }

        // Anstellungsarten Filter
        if (!in_array(-1, $this->selectedAnstellungsarten)) {
            $query->whereHas('mitarbeiter', function ($q) {
                $q->whereIn('anstellung', $this->selectedAnstellungsarten);
            });
        }

        // Besoldungen Filter
        if (!in_array(-1, $this->selectedBesoldungen)) {
            $query->whereHas('mitarbeiter', function ($q) {
                $q->whereIn('besoldung', $this->selectedBesoldungen);
            });
        }

        // Beurteilter Filter
        if (!in_array(-1, $this->selectedBeurteilter)) {
            $query->whereIn('mitarbeiterid', $this->selectedBeurteilter);
        }


        $this->sql = $query->toRawSql();

        return $query->get();
    }

    public function export(){
        $this->filename = "./temp/". $this->mitarbeiternr.date('yyddmmhhmmss', time() ).".csv" ;
        // echo "Dateiname := " . $this->filename . "<br>" ;


        $fp = fopen ( $this->filename, "w+" ) ;
        if ($fp)
        {
            flock($fp, 2) ;
            dd($fp);
            $data = $this->getDetailResult(false) ;

            if (count($data) > 0) {
            $first = true ;
            foreach( $data as $rec){
                if ($first){
                    fputcsv($fp, $rec->getHeaderColumns(), ';', '"' ) ;
                    $first = false ;
                }
                fputcsv($fp, $rec->getColumns(), ';', '"' ) ;
            }
            }
            else
            {
                $s = 'Keine Beurteilungen zum Export vorhanden.' ;
                fputs($fp, $s, strlen($s));
            }
            flock($fp, 3) ;
            fclose($fp);
            return $data ;
        }
        else
        {
            echo "Daten kÃ¶nnen nicht geschrieben werden." ;
            die ;
        }
    }


    public function downloadCSV()
    {
        //ob_clean();
        //ob_start();

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');



            // Kopfzeilen für die CSV (optional)
            $header = ['Name', 'Vorname', 'Stellenbezeichnung', 'Amt', 'Besoldung', 'Anstellung', 'Gesamtnote', 'Version'];



            fputcsv($handle, $header, ';');



            // Iteration über Beurteilungen
            foreach ($this->beurteilungen as $beurteilung) {
                $noten = $this->getDetails($beurteilung);


                $row = [
                    $beurteilung->mitarbeiter->name,
                    $beurteilung->mitarbeiter->vorname,
                    $beurteilung->stelle->bezeichnung,
                    $beurteilung->amt,
                    $beurteilung->mitarbeiter->besoldung,
                    $beurteilung->anstellungStr(),
                    $beurteilung->gesamtNoteStr(),
                    $beurteilung->version,
                ];
                foreach ($noten as $note){

                    $row[] = $note['beurteiler1note'];
                    $row[] = $note['beurteiler2note'];
                }
                

                fputcsv($handle, $row, ';');
            }

            fclose($handle);
        }, Auth::user()->personalnr."_". date('ymdHis') . '.csv');
    }


    function getDetails($beurteilung){


        if ($beurteilung->version == 2)
            $kriterien = Kriterien::where('art', 10 )->orderBy('nummer')->get();
        else
            $kriterien = Kriterien::where('art', 0 )->orderBy('nummer')->get();

        $details = [];


        foreach ($kriterien as $kriterium){


            $detail = Bdetails::where('beurteilungid', $beurteilung->id)
                    ->where('beurteilungsmerkmalid', $kriterium->id)
                    ->first();

            if ($detail){
                $details[] =
                    [
                        'beurteiler1note' =>  $detail->beurteiler1note,
                        'beurteiler2note' =>  $detail->beurteiler2note,
                    ];

            }
            else{
                $details[] = [
                        'beurteiler1note' => 'nicht benotet',
                        'beurteiler2note' => 'nicht benotet',
                    ];
            }
        }

        return $details;
    }


}

<?php
namespace App\Livewire;

use Hamcrest\Text\IsEmptyString;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use DateTime;
use DateInterval;


use Livewire\Component;
use App\Models\Beurteilung;
use App\Models\Mitarbeiter;
use App\Models\Stelle;
use App\Models\Kriterien;
use App\Models\Bdetails;
use App\Models\BPosDetail;
use App\Models\Meldung;
use App\Models\Config;

use App\Mail\Beurteiler1AbgeschlossenMail;


require_once base_path('app/Models/const.php');


class BeurteilungCreate extends Component
{

    public $mbeurteilung;

    public $mmitarbeiter;
    public $mstelle;
    public $mbeurteiler1;
    public $mbeurteiler2;

    public $mstelleB1;
    public $mstelleB2;

    public $kriterien;

    public $grenze;

    public $bdetails;
    public $details;


    public $id = '-1';
    public $mitarbeiterid = '-1';
    public $mitarbeiterfuehrung = 0;
    public $beurteiler1 = 0;
    public $beurteiler2 = 0;
    public $stelleid = 0;
    public $stellebeurteiler1 = '';
    public $stellebeurteiler2 = '';
    public $stellebeurteilter = '';
    public $datum = '';
    public $abgabedatum;
    public $bemerkung1 = '';
    public $bemerkung2 = '';
    public $gesamtnote1 = '';
    public $gesamtnote2 = '';
    public $gesamtnote1begruendung = '';
    public $gesamtnote2begruendung = '';
    public $regelbeurteilung = 0;  // 1 = Regel 0 = Bedarf  2 = Probezeit
    public $beurteilungszeitpunkt = -1; // 0 = Hälfte 1 = Ende
    public $abgeschlossen1 = 0;
    public $abgeschlossen2 = 0;
    public $veraltet = 0;
    public $zusatz1 = '';
    public $zusatz2 = '';
    public $besoldung = '';
    public $zeitraumvon = '';
    public $zeitraumbis = '';
    public $aufgabenbereich = '';
    public $anlass = '';
    public $ledit1;
    public $ledit2;
    public $nr_gesetzt = '';
    public $anstellung = '';
    public $teilzeit = '';
    public $amt = '';
    public $geeignet1 = '';
    public $geeignet2 = '';
    public $version = '';

    public $isModified;
    public $showTextarea = false;

    public $kommando;

    public $gesamtnote1Error = false;
    public $gesamtnote2Error = false;
    public $gesamtnote1begruendungError = false;
    public $gesamtnote2begruendungError = false;


    public $beurteiler1Abgabebereit = false ;
    public $beurteiler2Abgabebereit = false ;

    public $beurteiler1AbgabebereitText = '';
    public $beurteiler2AbgabebereitText = '';


    public $activeUser;

    public $meldungBemerkung;

    public $meldungen;

    public $editFld;
    public $editFldHeader;
    public string $activeTextarea = 'aufgabenbereich';

    public function mount($mid){
        $this->reset();

        $this->mmitarbeiter = Mitarbeiter::findOrFail($mid);
        $this->mstelle = Stelle::find($this->mmitarbeiter->stelle);



        $this->mbeurteiler1 = Mitarbeiter::getMitarbeiterUeberStelleByStelle($this->mstelle);
        $this->mstelleB1 = Stelle::find($this->mbeurteiler1->stelle);

        $this->mbeurteiler2 = Mitarbeiter::getMitarbeiterUeberStelleByStelle($this->mstelleB1);
        $this->mstelleB2 = Stelle::find($this->mbeurteiler2->stelle);


        if ( Auth::id() == $this->mbeurteiler1->id ){
            $this->activeUser = 1;
            $this->mbeurteilung = Beurteilung::where('mitarbeiterid', $mid)->where('abgeschlossen1', 0)->first();

        }
        else
            if ( Auth::id() == $this->mbeurteiler2->id ){
                $this->activeUser = 2;
                // dd(var_dump(Beurteilung::where('mitarbeiterid', $mid)->where('abgeschlossen2', 0)->toRawSql()));
                $this->mbeurteilung = Beurteilung::where('mitarbeiterid', $mid)->where('abgeschlossen2', 0)->first();

            }
        //dd($this->mbeurteilung);

        $this->grenze = \DateTime::createFromFormat('Y-m-d', '2022-01-01');


        if( is_null($this->mbeurteilung)){
            $this->mbeurteilung = new Beurteilung();
            $this->initBeurteilungVorCreate();

            if ($this->mbeurteilung->datum >= $this->grenze) {
                $this->version = 2;
            }
            else
                $this->version = 1;
        }






        $this->beurteilungToThis();

        if ($this->version == 2)
            $art = 10;
        else
            $art = 0;

        if ($this->mstelle->fuehrungskompetenz === true){
            $this->kriterien = Kriterien::where('art', $art )->orderBy('nummer', 'asc')->get();
        }
        else {
            $this->kriterien = Kriterien::where('art', $art )->where('fuehrungsmerkmal', false )->orderBy('nummer', 'asc')->get();
        }



        $this->details = [];
        foreach ($this->kriterien as $kriterium){
            //Log::Info('$be = null');
            $be = null ;
            if ( !is_null($this->mbeurteilung->id)){
                $be = Bdetails::where('beurteilungid', $this->mbeurteilung->id)->where('beurteilungsmerkmalid', $kriterium->id)->first();
                //Log::info('be-load', [ $be ]);
            }

            if (empty($be)) {
               // Log::Info('$be = null');
                $be = new Bdetails();
             //   Log::info('be-new', [ $be->id ]);
            }
            else
            {
           //     Log::Info('$be <> null');
            }
           // dd($be);
            //if (!empty($be->id))
           // Log::info('ID', [ $be->id ]);
            {
                $this->details[$kriterium->id] = [
                    'k' => $kriterium,
                    'w' => [
                        'id' => $be->id,
                        'beurteilungid' => $be->beurteilungid,
                        'beurteilungsmerkmalid' => $be->beurteilungsmerkmalid,
                        'beurteiler1note' => $be->beurteiler1note,
                        'beurteiler2note' => $be->beurteiler2note,
                        'beurteiler1bemerkung' => $be->beurteiler1bemerkung,
                        'beurteiler2bemerkung' => $be->beurteiler2bemerkung,
                        'zusatz' => $be->zusatz,
                        'beurteiler1laenderung' => $be->beurteiler1laenderung,
                        'beurteiler2laenderung' => $be->beurteiler2laenderung,
                        'beurteiler1noteError' => false,
                        'beurteiler2noteError' => false,

                        'beurteiler1bemerkungError' => ( $be->beurteiler1note == 1 ||  $be->beurteiler1note == 4 ) && empty(trim($be->beurteiler1bemerkung)),
                        'beurteiler2bemerkungError' => ( $be->beurteiler2note == 1 ||  $be->beurteiler2note == 4 ) && empty(trim($be->beurteiler2bemerkung)),
                    ]
                ];
            }

        }

        $this->doValidate();

        $this->meldungen = $this->ReadMeldungen($this->mbeurteilung->id);


        //dd ($this->details);

    }

    public function render() {
        //dd($this->details);
        return view('livewire.beurteilung.create');
    }


    public function thisToBeurteilung(){

        // Validierung der Felder
        $this->validate([
            'mitarbeiterid' => 'required|integer',
            'datum' => 'required|date',
            'zeitraumvon' => 'required|date',
            'zeitraumbis' => 'required|date|after:zeitraumvon',
        ]);

        $this->mbeurteilung->id = $this->id;
        $this->mbeurteilung->mitarbeiterid = $this->mitarbeiterid;
        $this->mbeurteilung->mitarbeiterfuehrung = $this->mitarbeiterfuehrung;
        $this->mbeurteilung->beurteiler1 = $this->beurteiler1;
        $this->mbeurteilung->beurteiler2 = $this->beurteiler2;
        $this->mbeurteilung->stelleid = $this->stelleid;
        $this->mbeurteilung->stellebeurteiler1 = $this->stellebeurteiler1;
        $this->mbeurteilung->stellebeurteiler2 = $this->stellebeurteiler2;
        $this->mbeurteilung->stellebeurteilter = $this->stellebeurteilter;
        $this->mbeurteilung->datum = $this->datum;
        if ( is_Null($this->abgabedatum)){
            $this->mbeurteilung->abgabedatum = \DateTime::createFromFormat('Y-m-d', '1970-01-01' );
        }
        else{
            $this->mbeurteilung->abgabedatum = $this->abgabedatum;
        }
        $this->mbeurteilung->bemerkung1 = $this->bemerkung1;
        $this->mbeurteilung->bemerkung2 = $this->bemerkung2;
        $this->mbeurteilung->gesamtnote1 = $this->gesamtnote1;
        $this->mbeurteilung->gesamtnote2 = $this->gesamtnote2;
        $this->mbeurteilung->gesamtnote1begruendung = $this->gesamtnote1begruendung;
        $this->mbeurteilung->gesamtnote2begruendung = $this->gesamtnote2begruendung;
        $this->mbeurteilung->regelbeurteilung = $this->regelbeurteilung;
        $this->mbeurteilung->beurteilungszeitpunkt = $this->beurteilungszeitpunkt;
        if ($this->mbeurteilung->regelbeurteilung != 2){
            $this->mbeurteilung->beurteilungszeitpunkt = -1;
        }

        $this->mbeurteilung->abgeschlossen1 = $this->abgeschlossen1;
        $this->mbeurteilung->abgeschlossen2 = $this->abgeschlossen2;
        $this->mbeurteilung->veraltet = $this->veraltet;
        $this->mbeurteilung->zusatz1 = $this->zusatz1;
        $this->mbeurteilung->zusatz2 = $this->zusatz2;
        $this->mbeurteilung->besoldung = $this->besoldung;
        $this->mbeurteilung->zeitraumvon = $this->zeitraumvon ; // \DateTime::createFromFormat('Y-m-d', $this->zeitraumvon );
        $this->mbeurteilung->zeitraumbis = $this->zeitraumbis ; //\DateTime::createFromFormat('Y-m-d',  );
        $this->mbeurteilung->aufgabenbereich = $this->aufgabenbereich;
        $this->mbeurteilung->anlass = $this->anlass;
        $this->mbeurteilung->ledit1 = $this->ledit1;
        $this->mbeurteilung->ledit2 = $this->ledit2;

        if ($this->activeUser == 1) {
                $this->mbeurteilung->ledit1 = \today();
                // Log::info("Beurteiler2 ledit1 wird gesetzt");
            }
        else{
            $this->mbeurteilung->ledit2 = \today();
            // Log::info("Beurteiler2 ledit2 wird gesetzt");
        }
        // Log::info('thisToBeurteilung Z210 $this->ledit2 ', [$this->ledit2]);
        // Log::info('thisToBeurteilung Z210 $this->mbeurteilung->ledit2 ', [$this->mbeurteilung->ledit2]);
        $this->mbeurteilung->nr_gesetzt = $this->nr_gesetzt;
        $this->mbeurteilung->anstellung = $this->anstellung;
        $this->mbeurteilung->teilzeit = $this->teilzeit;
        $this->mbeurteilung->amt = $this->amt;
        $this->mbeurteilung->geeignet1 = $this->geeignet1;
        $this->mbeurteilung->geeignet2 = $this->geeignet2;
        $this->mbeurteilung->version = $this->version;
    }

    public function beurteilungToThis(){
        $this->id = $this->mbeurteilung->id;
        $this->mitarbeiterid = $this->mbeurteilung->mitarbeiterid;
        $this->mitarbeiterfuehrung = $this->mbeurteilung->mitarbeiterfuehrung;
        $this->beurteiler1 = $this->mbeurteilung->beurteiler1;
        $this->beurteiler2 = $this->mbeurteilung->beurteiler2;
        $this->stelleid = $this->mbeurteilung->stelleid;
        $this->stellebeurteiler1 = $this->mbeurteilung->stellebeurteiler1;
        $this->stellebeurteiler2 = $this->mbeurteilung->stellebeurteiler2;
        $this->stellebeurteilter = $this->mbeurteilung->stellebeurteilter;
        $this->datum = $this->mbeurteilung->datum;
        $this->abgabedatum = $this->mbeurteilung->abgabedatum;
        $this->bemerkung1 = $this->mbeurteilung->bemerkung1;
        $this->bemerkung2 = $this->mbeurteilung->bemerkung2;
        $this->gesamtnote1 = $this->mbeurteilung->gesamtnote1;
        $this->gesamtnote2 = $this->mbeurteilung->gesamtnote2;
        $this->gesamtnote1begruendung = $this->mbeurteilung->gesamtnote1begruendung;
        $this->gesamtnote2begruendung = $this->mbeurteilung->gesamtnote2begruendung;
        $this->regelbeurteilung = $this->mbeurteilung->regelbeurteilung;
        $this->beurteilungszeitpunkt = $this->mbeurteilung->beurteilungszeitpunkt;
        if ($this->regelbeurteilung != 2){
            $this->beurteilungszeitpunkt = -1;
        }
        $this->abgeschlossen1 = $this->mbeurteilung->abgeschlossen1;
        $this->abgeschlossen2 = $this->mbeurteilung->abgeschlossen2;
        $this->veraltet = $this->mbeurteilung->veraltet;
        $this->zusatz1 = $this->mbeurteilung->zusatz1;
        $this->zusatz2 = $this->mbeurteilung->zusatz2;
        $this->besoldung = $this->mbeurteilung->besoldung;
        $this->zeitraumvon =   $this->mbeurteilung->zeitraumvon;
        $this->zeitraumbis = $this->mbeurteilung->zeitraumbis;
        $this->aufgabenbereich = $this->mbeurteilung->aufgabenbereich;
        $this->anlass = $this->mbeurteilung->anlass;
        $this->ledit1 = $this->mbeurteilung->ledit1;
        $this->ledit2 = $this->mbeurteilung->ledit2;

        // Log::info('beurteilungToThis Z249 $this->mbeurteilung->ledit2 ', [$this->mbeurteilung->ledit2]);
        // Log::info('beurteilungToThis Z249 $this->ledit2 ', [$this->ledit2]);


        $this->nr_gesetzt = $this->mbeurteilung->nr_gesetzt;
        $this->anstellung = $this->mbeurteilung->anstellung;
        $this->teilzeit = $this->mbeurteilung->teilzeit;
        $this->amt = $this->mbeurteilung->amt;
        $this->geeignet1 = $this->mbeurteilung->geeignet1;
        $this->geeignet2 = $this->mbeurteilung->geeignet2;
        $this->version = $this->mbeurteilung->version;
    }

    private function initBeurteilungVorCreate(){

        $this->mbeurteilung->mitarbeiterid = $this->mmitarbeiter->id ;
        $this->mbeurteilung->mitarbeiterfuehrung = $this->mstelle->fuehrungskompetenz;
        $this->mbeurteilung->beurteiler1 = $this->mbeurteiler1->id;
        $this->mbeurteilung->beurteiler2 = $this->mbeurteiler2->id;
        $this->mbeurteilung->stelleid = $this->mstelle->id;
        $this->mbeurteilung->stellebeurteilter = $this->mstelle->bezeichnung;

        $this->mbeurteilung->stellebeurteiler1 = $this->mstelleB1->bezeichnung ;
        $this->mbeurteilung->stellebeurteiler2 = $this->mstelleB2->bezeichnung ;
        $this->mbeurteilung->datum = today();
        $this->mbeurteilung->regelbeurteilung = 1;
        $this->mbeurteilung->besoldung = $this->mmitarbeiter->besoldung;
        $this->mbeurteilung->zeitraumvon = date("Y-m-d", mktime(0, 0, 0, date("m")-2, 1, date("Y") ) );

        //$this->mbeurteilung->zeitraumvon = date("d.m.Y", mktime(0, 0, 0, date("m")-2, 1,  date("Y")) );
        $this->mbeurteilung->zeitraumbis = date('Y-m-d');

        $this->mbeurteilung->abgabedatum = null ;
        $this->mbeurteilung->gesamtnote1 = 0;
        $this->mbeurteilung->gesamtnote2 = 0;
        $this->mbeurteilung->ledit1 = null;
        $this->mbeurteilung->ledit2 = null;
        $this->mbeurteilung->amt = $this->mmitarbeiter->amt;


        // Log::info('initBeurteilungVorCreate Z274 $this->mbeurteilung->ledit2 ', [$this->mbeurteilung->ledit2]);
        // dd($this->mbeurteilung->ledit2);
    }

    public function updated($name, $value){

        // Sofortige Validierung bei jedem Update des Feldes

        // 0 = Bedarf
        // 1 = Regel
        // 2 = Probezeit
        //  Dann 0 Zur Hälfte oder 1 zum Ende
        if ($this->regelbeurteilung != 2){
            $this->beurteilungszeitpunkt = -1;
        }
        elseif ($this->regelbeurteilung == 2){
            Log::info('beurteilungszeitpunkt', [$this->beurteilungszeitpunkt]);
            if ($this->beurteilungszeitpunkt === -1){
                $this->beurteilungszeitpunkt = 0;
            }
        }


        $this->doValidate();

    }

   public function doValidate(){

    if ($this->activeUser === 1) { // Beurteiler 1 aktiv
        $this->beurteiler1Abgabebereit = false;
        $this->beurteiler2Abgabebereit = false;
    } elseif ($this->activeUser === 2) { // Beurteiler 1 aktiv
        $this->beurteiler1Abgabebereit = true;
        $this->beurteiler2Abgabebereit = false;
    }

    $someError = false ;

    if ($this->beurteilungszeitpunkt != 0){
        foreach ($this->details as $id => $detail ){
            // Log::info('foreach', [ $id, $detail ] );

            if ($this->activeUser === 1) { // Beurteiler 1 aktiv
                if (($detail['w']['beurteiler1note']== 1 || $detail['w']['beurteiler1note'] == 4) && (empty($detail['w']['beurteiler1bemerkung'] ))){
                    //Log::info('beurteiler1bemerkungError', [true]);

                    $this->details[$id]['w']['beurteiler1bemerkungError'] = true;
                    $someError = true ;
                } else {
                    //Log::info('beurteiler1bemerkungError', [false]);
                    $this->details[$id]['w']['beurteiler1bemerkungError'] = false;
                }
                if ($detail['w']['beurteiler1note'] < 1 || $detail['w']['beurteiler1note'] > 4) {
                    $this->details[$id]['w']['beurteiler1noteError'] = true;
                    $someError = true ;
                }
                else
                {
                    $this->details[$id]['w']['beurteiler1noteError'] = false;
                }


                // Log::info( '$this->beurteiler1Abgabebereit', [$this->beurteiler1Abgabebereit]);

            } elseif ($this->activeUser === 2) { // Beurteiler 2 aktiv
                // Log::info( '$this->beurteiler2note', [$detail['w']['beurteiler2note']]);
                if (
                    ($detail['w']['beurteiler2note']== 1 || $detail['w']['beurteiler2note'] == 4) &&
                    (empty($detail['w']['beurteiler2bemerkung'] )
                   )){
                    $this->details[$id]['w']['beurteiler2bemerkungError'] = true;
                    $someError = true ;
                } else { // Kein Fehler
                    $this->details[$id]['w']['beurteiler2bemerkungError'] = false;
                }

                if ($detail['w']['beurteiler2note'] < 1 || $detail['w']['beurteiler2note'] > 4) {
                    $this->details[$id]['w']['beurteiler2noteError'] = true;
                    $someError = true ;
                }
                else{
                    $this->details[$id]['w']['beurteiler2noteError'] = false;
                }


                // Log::info( '$this->beurteiler2Abgabebereit', [$this->beurteiler2Abgabebereit]);
            }
        }
        if ($this->activeUser === 1) {
                $this->gesamtnote1begruendungError = (($this->gesamtnote1== 1 || $this->gesamtnote1 == 4) && (empty($this->gesamtnote1begruendung )));
                $this->gesamtnote1Error = ($this->gesamtnote1 < 1 || $this->gesamtnote1 > 4);
                $this->beurteiler1Abgabebereit = !($someError || $this->gesamtnote1begruendungError || $this->gesamtnote1Error);
            }
        else {
                $this->gesamtnote2begruendungError = (($this->gesamtnote2== 1 || $this->gesamtnote2 == 4) && (empty($this->gesamtnote2begruendung )));
                $this->gesamtnote2Error = ($this->gesamtnote2 < 1 || $this->gesamtnote2 > 4);
                $this->beurteiler2Abgabebereit = !($someError || $this->gesamtnote2begruendungError || $this->gesamtnote2Error);
        }

    }
    else{
        foreach ($this->details as $id => $detail ){
            if ($this->activeUser === 1) { // Beurteiler 1 aktiv
                $this->details[$id]['w']['beurteiler1bemerkungError'] = false;
                $this->details[$id]['w']['beurteiler1noteError'] = false;
            } elseif ($this->activeUser === 2) { // Beurteiler 2 aktiv
                $this->details[$id]['w']['beurteiler2bemerkungError'] = false;
                $this->details[$id]['w']['beurteiler2noteError'] = false;

            }
        }
        if ($this->activeUser === 1) { // Beurteiler 1 aktiv
            $this->gesamtnote1begruendungError = false;
            $this->gesamtnote1Error = false ;
            $this->beurteiler1Abgabebereit = true;
        } elseif ($this->activeUser === 2) { // Beurteiler 2 aktiv

            $this->gesamtnote2begruendungError = false;
            $this->gesamtnote2Error = false ;
            $this->beurteiler2Abgabebereit = true;
        }

    }

    $this->validate( [
        'zeitraumvon' => 'required|date|before:zeitraumbis',
        'zeitraumbis' => 'required|date|after:zeitraumvon',
    ]);

    if ($this->beurteiler1Abgabebereit){
        $this->beurteiler1AbgabebereitText = "Beurteiler 1: Sie sind abgabebereit. Kein Formfehler gefunden!";
    }
    else
        $this->beurteiler1AbgabebereitText = "Beurteiler 1: Sie noch nicht abgabebereit. Es wurden Formfehler gefunden!";

    if ($this->beurteiler2Abgabebereit){
        $this->beurteiler2AbgabebereitText = "Beurteiler 2: Sie sind abgabebereit. Kein Formfehler gefunden!";
    }
    else
        $this->beurteiler2AbgabebereitText = "Beurteiler 2: Sie noch nicht abgabebereit. Es wurden Formfehler gefunden!";

        if ($this->activeUser === 1) {
            return $this->beurteiler1Abgabebereit;
        }
        else{
            return $this->beurteiler2Abgabebereit;
        }

   }

    public function save(){

        // Log::info('BeurteilungCreate.php save() - Anfang');
        //Log::info('  Stack: ',[ debug_backtrace()] );
        $this->thisToBeurteilung();

        try{

				// Änderung vom 25.01.2014
				// Wenn Beurteiler 1 aktiv und die Beurteilung noch nicht von Beurteiler 2 bearbeitet wurde, dann
				// immer die Begründung von Beurteiler 1 übernehmen
				if (is_null($this->mbeurteilung->ledit2)) {
                    $this->mbeurteilung->gesamtnote2begruendung = $this->mbeurteilung->gesamtnote1begruendung ;
                    $this->mbeurteilung->zusatz2 = $this->mbeurteilung->zusatz1;
                    $this->mbeurteilung->bemerkung2 = $this->mbeurteilung->bemerkung1;
                    //if (array_key_exists( 'geeignet1', $this->formData ) and !is_null( $this->formData['geeignet1'] )) {
                        $this->mbeurteilung->geeignet2 = $this->mbeurteilung->geeignet1;
                    //}
                }
                if ( strtolower($this->kommando) === TEXT_ABGESCHLOSSEN ) {
                    if ( $this->activeUser === 1){
                        $this->mbeurteilung->abgeschlossen1 = ($this->beurteiler1Abgabebereit) ? 1 : 0 ;
                    }
                    else {
                        $this->mbeurteilung->abgeschlossen2 =  ($this->beurteiler2Abgabebereit) ? 1 : 0 ;
                        $this->mmitarbeiter->nbeurteilung = $this->calcNBeurteilung( $this->mmitarbeiter->nbeurteilung, $this->mmitarbeiter->anstellung, $this->mbeurteilung->regelbeurteilung) ;
                        $this->mmitarbeiter->save();
                    }

                }
                elseif ( strtolower($this->kommando) === TEXT_ZURUECK && $this->activeUser === 2){
                    $this->mbeurteilung->abgeschlossen1 = 0 ;
                }

                $this->mbeurteilung->abgabedatum = $this->calcAbgabedatum();
                if ($this->mbeurteilung->save()){
                    $this->id = $this->mbeurteilung->id;

                }

            if ( $this->mbeurteilung->regelbeurteilung != 2) {
                foreach ($this->details as $key => $detail){

                    // dd($detail);
                    if ( !is_null($detail['w']['id'])){
                        $de = Bdetails::where('beurteilungid', $detail['w']['beurteilungid'])->where('beurteilungsmerkmalid', $detail['w']['beurteilungsmerkmalid'])->first();
                    }
                    else{
                        $de = new Bdetails();
                    }


                    $de->beurteilungid = $this->mbeurteilung->id;
                    $de->beurteilungsmerkmalid = $key;
                    if ($this->activeUser == 1){
                        $de->beurteiler1note = $detail['w']['beurteiler1note'];
                        $de->beurteiler1bemerkung = $detail['w']['beurteiler1bemerkung'];
                        $de->beurteiler2bemerkung = $de->beurteiler1bemerkung;
                        $de->beurteiler1laenderung = now();
                    }
                    else{
                        $de->beurteiler2note = $detail['w']['beurteiler2note'];
                        $de->beurteiler2bemerkung = $detail['w']['beurteiler2bemerkung'];
                        $de->beurteiler2laenderung = now();

                    }
                    // Log::info("save", [ $de->beurteilungid, $de->beurteilungsmerkmalid, $de->beurteiler1note ]);
                    $de->save();
                    $this->showTextarea = false ;

                }
            }

            // Meldung speichern für Beurteiler 1
            if ( strtolower($this->kommando) === TEXT_ZURUECK && $this->activeUser === 2 )
                {
                    if (trim($this->meldungBemerkung) <> '' ){
                        $mmeldung 						= new Meldung() ;

                        $mmeldung->mitarbeiter		= $this->mbeurteilung->beurteiler2 ;
                        $mmeldung->anmitarbeiter 	= $this->mbeurteilung->beurteiler1 ;
                        $mmeldung->nachricht		= $this->meldungBemerkung;
                        $mmeldung->zielid			= $this->mbeurteilung->id;
                        $mmeldung->art				= ART_BEURTEILUNG ;
                        $mmeldung->save();



                    }

                    return redirect()->route('beurteilung')->with('success', 'Beurteilung wurde zurück an Beurteiler 1 übertragen!');

                }
            else
            if ( strtolower($this->kommando) === TEXT_ABGESCHLOSSEN && $this->activeUser === 1 && $this->mbeurteilung->abgeschlossen1 === 1)
                {
                    if (trim($this->meldungBemerkung) <> '' ){
                        $mmeldung 						= new Meldung() ;

                        $mmeldung->mitarbeiter		= $this->mbeurteilung->beurteiler1 ;
                        $mmeldung->anmitarbeiter 	= $this->mbeurteilung->beurteiler2 ;
                        $mmeldung->nachricht		= $this->meldungBemerkung;
                        $mmeldung->zielid			= $this->mbeurteilung->id;
                        $mmeldung->art				= ART_BEURTEILUNG ;
                        $mmeldung->save();
                    }

                    $this->SendBeurteiler1AbgeschlossenMail();

                    return redirect()->route('beurteilung')->with('success', 'Beurteilung wurde abgeschlossen und die Bestätigung per Mail versandt!');

                }
                else
                if ( strtolower($this->kommando) === TEXT_ABGESCHLOSSEN && $this->activeUser === 2  && $this->mbeurteilung->abgeschlossen2 === 1)
                {
                    return redirect()->route('beurteilung')->with('success', 'Beurteilung wurde abgeschlossen!');
                }
                else
                    return redirect()->route('beurteilung')->with('success', 'Beurteilung wurde gespeichert!');

            }
        catch(\Exception $e){

            //$this->dispatch("Fehler: ", [$e->getMessage()]);
            // request()->session()->flash('error', "Fehler: ".$e->getMessage());

            Log::error("Fehler beim Speichern der Beurteilung Zeile 619: ".$e->getMessage());

        }

    }


    public function doShowTextarea($fieldName){

        $this->editFldHeader = $this->getLabelForField($fieldName);
        $this->activeTextarea = $fieldName;

        $this->showTextarea = true;
    }

    public function getLabelForField($field): string
    {
        // 1. Feste Felder abfangen
        return match (true) {
            $field === 'aufgabenbereich' => 'Aufgabenbereich',
            $field === 'zusatz1' => 'Zusatzbemerkung Beurteiler 1',
            $field === 'zusatz2' => 'Zusatzbemerkung Beurteiler 2',
            $field === 'gesamtnote1begruendung' => 'Begründung Gesamtnote 1',
            $field === 'gesamtnote2begruendung' => 'Begründung Gesamtnote 2',

            // 2. Dynamische Kriterien-Felder abfangen
            str_starts_with($field, 'details.') => $this->labelFromKriterien($field),

            // 3. Fallback
            default => 'Bemerkung',
        };
    }

    protected function labelFromKriterien(string $field): string
    {
        // Regex: hole die ID zwischen 'details.' und '.w.'
        if (preg_match('/details\.(\d+)\.w\./', $field, $matches)) {
            $id = (int) $matches[1];

            // Hole das Kriterium
            $kriterium = \App\Models\Kriterien::find($id);

            if ($kriterium) {
                return 'Bemerkung zur ' . $kriterium->ueberschrift;
            }
        }

        return 'Bemerkung';
    }

	private function calcNBeurteilung ( $param_nb, $param_anstellung, $beurteilungArt ){
		// Parameter:
		//    param_nb         = Nächste Beurteilung
		// 	  param_anstellung = Dienstgrad

		list($jahr,$mm,$dd)    =    explode('-', $param_nb);


		switch ($param_anstellung) {
			case 1: // Angestellter einfacher Dienst
			case 2: // Angemeldeter mittlerer Dienst
			case 5: // Beamter mittlerer Dienst
			case 8: // Beamter Probezeit
			case 3: // Angestellter gehobener Dienst
			case 4: // Angestellter höherer Dienst
			case 6: // Beamter gehobener Dienst
			case 7: // Beamter höherer Dienst
				if ( $beurteilungArt == 1 ) // Regelbeurteilung
				{
					$temp = ($jahr + 3).'-02-15' ;
				}
				else
				{
					$temp = ($jahr + 3).'-02-15' ;

					$heute = date('Y-m-d');

					$d1 = date_create_from_format('Y-m-d', $temp);
					$d2 = date_create_from_format('Y-m-d', $heute);

					$diff = (array) date_diff($d1, $d2);
					if ( $diff['y'] > 0 ) {
						$temp = ($jahr + 3).'-02-15';
					}
					else {
						$temp = ($jahr + 6).'-02-15';
					}
				}
				break;
		}
		return $temp ;
	}

	private function calcAbgabedatum ( ) {
		$stdDate1  	= new DateTime( );
		$stdDate1->add ( new DateInterval ( 'P30D' ) ) ;
		$temp = $stdDate1->format('Y-m-d') ;
		return $temp ;
	}

    private function ReadMeldungen($beurteilungId){
        try {
            // Eloquent-Abfrage
            $rows = Meldung::select('meldungen.*', 'm1.name as name', 'm1.vorname as vorname', 'm2.name as anname', 'm2.vorname as anvorname')
                ->leftJoin('users as m1', 'm1.id', '=', 'meldungen.mitarbeiter') // Verknüpfung mit dem Mitarbeiter, der die Meldung gemacht hat
                ->leftJoin('users as m2', 'm2.id', '=', 'meldungen.anmitarbeiter') // Verknüpfung mit dem Mitarbeiter, an den die Meldung ging
                ->where('meldungen.art', 400) // Filter nach Art
                ->where('meldungen.zielid', $beurteilungId) // Filter nach Ziel-ID
                ->orderBy('meldungen.created_at', 'desc') // Sortierung nach Datum absteigend
                ->get();
                //->ToRawSql(); // Ergebnis abrufen

            return $rows;

        } catch (\Exception $e) {
            // Fehlerbehandlung
            logger()->error('Fehler in getListfromBeurteilung: ' . $e->getMessage());
            throw $e; // Optional: Wirf die Exception erneut oder handle sie nach Bedarf
        }
    }

    private function SendBeurteiler1AbgeschlossenMail(){

        //Log::info('SendBeurteiler1AbgeschlossenMail - Anfang');
        $details = [
            'beurteiler1anrede' => $this->mbeurteiler1->anrede,
            'beurteiler1name' => $this->mbeurteiler1->name,

            'beurteiler2anrede' => $this->mbeurteiler2->anrede,
            'beurteiler2name' => $this->mbeurteiler2->name,

            'beurteilteranrede' => $this->mmitarbeiter->anrede,
            'beurteiltername' => $this->mmitarbeiter->name,

        ];

        $to = [];
        $cc = [];

        if (Config::isTest()) {
            $to = [env('EMAIL_TEST', 'mail@andreasalbers.de') ] ; //=> "{$this->mbeurteiler1->vorname} {$this->mbeurteiler1->name} [test]"];
            $cc = [env('EMAIL_TEST_CC', 'mail@andreasalbers.de') ] ; // => "{$this->mbeurteiler2->vorname} {$this->mbeurteiler2->name} [test]"];
        } else {
            $cc = [ $this->mbeurteiler2->email ];
            $to = [ $this->mbeurteiler1->email ];

        }

        //dd($to);
        Mail::to($to)
            ->cc($cc)
            ->send(new Beurteiler1AbgeschlossenMail($details));

        // Log::info('SendBeurteiler1AbgeschlossenMail - Ende');
    }



}





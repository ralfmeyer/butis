<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\BeurtStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class Mitarbeiter extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = ['id', 'personalnr', 'anrede', 'vorname', 'name', 'gebdatum', 'stelle', 'kennwort', 'anstellung', 'besoldung', 'lregelbeurteilung', 'nbeurteilung', 'amt', 'bemerkung', 'email', 'vertragsende', 'teilzeit', 'benachrichtigt', 'abgabedatum'];

    public static $anstellungTypes = [
        1 => "Angestellte/er einfacher Dienst",
        2 => "Angestellte/er mittlerer Dienst",
        3 => "Angestellte/er gehobener Dienst",
        4 => "Angestellte/er höherer Dienst",
        5 => "Beamte/er mittlerer Dienst",
        6 => "Beamte/er gehobener Dienst",
        7 => "Beamte/er höherer Dienst",
        8 => "Beamte/er Probezeit",
    ];

    public function stelle()
    {
        return $this->belongsTo(Stelle::class, 'stelle', 'id');
    }

    public function stelleBezeichnung()
    {
        return $this->belongsTo(Stelle::class, 'stelle');
    }

    public function beurteilung()
{
    return $this->hasMany(Beurteilung::class, 'mitarbeiterid');
}

    public static function getAnstellung($id)
    {
        return self::$anstellungTypes[$id] ?? 'Unbekannte Anstellung';
    }

    protected $_name = 'mitarbeiter';

    /* NEU 12.07.2024
    public function getStelleundEbene($id)
    {
          $adapter = $this->getAdapter();
          $select = $adapter->select()
                                  ->from( array('m'=>'mitarbeiter'), array( 'm.stelle' ) )
                                  ->join( array('st'=>'stellen'), 'st.id = m.stelle', array( 'st.ebene') )
                                     ->where( sprintf('m.id = %d', $id ) )  ;
             $row = $adapter->fetchRow( $select ) ;
             return $row ;
    }

NEU */

    private function getMitarbeiterID($mitarbeiterID)
    {
        if (!$mitarbeiterID) {
            return 79; // frye // Beispiel-ID, setze hier die tatsächliche ID
            //return = 21 ; // Blömer => Stelle=200 // Beispiel-ID, setze hier die tatsächliche ID
        }
    }

    public function search($suchtext)
    {
        /*
         * Alte orginal Anweisung 11.07.2024
         *  $rows = $this->fetchAll( $this->select()
         *                                  ->from( 'mitarbeiter', array( 'concat( vorname, " ", name ) as name' ) )
         *                                     ->where( sprintf('vorname like "%%%s%%" or name like "%%%s%%"', $suchtext, $suchtext  ) )
         *                                     ->order( Array ( 'vorname', 'name' ))
         *                             ) ;
         */

        $rows = DB::table('users')
            ->select(DB::raw('concat(vorname, " ", name) as name'))
            ->where(function ($query) use ($suchtext) {
                $query->where('vorname', 'like', "%{$suchtext}%")->orWhere('name', 'like', "%{$suchtext}%");
            })
            ->orderBy('vorname')
            ->orderBy('name')
            ->get();

        $mitarbeiter = [];
        $i = 0;
        foreach ($rows as $row) {
            $mitarbeiter[$i] = $row->name;
            $i++;
        }
        return $mitarbeiter;
    }

    /*
     * NEU 12.07.2024
     */
    // #region Meine Region
    public function getFaelligeMitarbeiterBeurteiler1($mitarbeiterID)
    {
        // Gibt ein array zurück mit ID's der zur Beurteilung fälligen Mitarbeiter unter der $mitarbeiterID
        // z.B. rows[0]->id = 417
        try {
           // Log::info('getFaelligeMitarbeiterBeurteiler1() Anfang ', [$mitarbeiterID]);



            $vorlauf = 60;

            $query = DB::table('users as m')
                ->select('mm.*')
                ->leftJoin('stellen as st1', 'st1.id', '=', 'm.stelle')
                ->leftJoin('stellen as st2', 'st2.uebergeordnet', '=', 'st1.id')
                ->leftJoin('users as mm', 'mm.stelle', '=', 'st2.id')
                ->where('m.id', $mitarbeiterID)
                ->whereNotExists(function ($query) {
                    $query
                        ->select(DB::raw(1))
                        ->from('beurteilung')
                        ->whereColumn('mitarbeiterid', 'mm.id')
                        ->where(function ($query) {
                            $query->where('abgeschlossen1', 0)->orWhere(function ($query) {
                                $query->where('abgeschlossen1', 1)->where('abgeschlossen2', 0);
                            });
                        });
                })
                ->where('mm.ausgeschieden', 0)
                ->where(function ($query) use ($vorlauf) {
                    $query->whereRaw('datediff(mm.nbeurteilung, curdate()) <= ?', [$vorlauf])->orWhereNull('mm.nbeurteilung');
                })
                ->orderBy('mm.name', 'asc')
                ->orderBy('mm.vorname', 'asc');

            $rows = $query->get();

            //Log::info('getFaelligeMitarbeiterBeurteiler1() Ende count Rows ', [count($rows)]);

            return $rows;
        } catch (\Exception $e) {
            echo 'Exception getFaelligeMitarbeiterBeurteiler1: ', $e->getMessage(), "\n";
            die();
        }
    }

    // #endregion

    /*
     * NEU 01.08.2024
     */

    public function getMitarbeiterArrayUnterBeurteiler1($mitarbeiterID)
    {
        // Gibt alle nicht ausgeschiedenen Mitarbeiter zurück
        // Rückgabe = Array of Integer = ID's
        // #region 01.08.2024
        try {
            $query = DB::table('users as m')->select('mm.id')->leftJoin('stellen as st1', 'st1.id', '=', 'm.stelle')->leftJoin('stellen as st2', 'st2.uebergeordnet', '=', 'st1.id')->leftJoin('users as mm', 'mm.stelle', '=', 'st2.id')->where('m.id', $mitarbeiterID)->where('mm.ausgeschieden', 0)
            ->orderBy('mm.name', 'asc')
            ->orderBy('mm.vorname', 'asc');
            // Log::info($query->toRawSQL());
            $rows = $query->get();

            $arr = [];
            foreach ($rows as $item) {
                if (!in_array($item->id, $arr) && $item->id != null) {
                    $arr[] = $item->id;
                }
            }

            return $arr;
        } catch (\Exception $e) {
            echo 'Exception getFaelligeMitarbeiterBeurteiler1: ', $e->getMessage(), "\n";
            die();
        }
    }
    // #endregion

    public function getMitarbeiterUnterBeurteiler1($mitarbeiterID)
    // Gibt ein array zurück mit ID's der zur Beurteilung fälligen Mitarbeiter unter der $mitarbeiterID
    // z.B. rows[0]->id = 417
//  #region 01.08.2024
    {
        try {
            $arr = $this->getMitarbeiterArrayUnterBeurteiler1($mitarbeiterID);

            $rows = $this->whereIn('id', $arr)->orderBy('name')->orderBy('vorname')->get();

            return $rows;
        } catch (\Exception $e) {
            echo 'Exception getFaelligeMitarbeiterBeurteiler1: ', $e->getMessage(), "\n";
            die();
        }
    }
//  #endregion


public static function getMitarbeiterUnterMitarbeiter($mitarbeiterID){
    $query = DB::table('users as m1')
        ->select('m1.id as M1_ID', 'm2.id as M2_ID', 'm3.id as M3_ID', 'm4.id as M4_ID', 'm5.id as M5_ID', 'm6.id as M6_ID')
        ->leftJoin('stellen as st1', 'st1.id', '=', 'm1.stelle') // ST1.id = 12 uebergeordnet = 6
        ->leftJoin('stellen as st2', 'st2.uebergeordnet', '=', 'st1.id') // ST2.id = 13 uebergeordnet = 12
        ->leftJoin('stellen as st3', 'st3.uebergeordnet', '=', 'st2.id') // ST3.id = NULL  => es gibt keine Stelle, die die ID 13 als übergeordnet hat
        ->leftJoin('stellen as st4', 'st4.uebergeordnet', '=', 'st3.id')
        ->leftJoin('stellen as st5', 'st5.uebergeordnet', '=', 'st4.id')
        ->leftJoin('stellen as st6', 'st6.uebergeordnet', '=', 'st5.id')
        ->leftJoin('stellen as st7', 'st7.uebergeordnet', '=', 'st6.id')
        ->leftJoin('users as m2', 'm2.stelle', '=', 'st2.id')
        ->leftJoin('users as m3', 'm3.stelle', '=', 'st3.id')
        ->leftJoin('users as m4', 'm4.stelle', '=', 'st4.id')
        ->leftJoin('users as m5', 'm2.stelle', '=', 'st5.id')
        ->leftJoin('users as m6', 'm2.stelle', '=', 'st6.id')
        ->where('m1.id', $mitarbeiterID);

    $results = $query->get();

    $arr = [];
    foreach ($results as $item) {
        if (!in_array($item->M1_ID, $arr) && $item->M1_ID != null) {
            $arr[] = $item->M1_ID;
        }
        if (!in_array($item->M2_ID, $arr) && $item->M2_ID != null) {
            $arr[] = $item->M2_ID;
        }
        if (!in_array($item->M3_ID, $arr) && $item->M3_ID != null) {
            $arr[] = $item->M3_ID;
        }
        if (!in_array($item->M4_ID, $arr) && $item->M4_ID != null) {
            $arr[] = $item->M4_ID;
        }
        if (!in_array($item->M5_ID, $arr) && $item->M5_ID != null) {
            $arr[] = $item->M5_ID;
        }
        if (!in_array($item->M6_ID, $arr) && $item->M6_ID != null) {
            $arr[] = $item->M6_ID;
        }
    }
    return $arr;

}

    public function getMitarbeiterAllerEbenenUnterBeurteiler1($mitarbeiterID)
    // Liefert alle Mitabarbeiter, bis sechs ebenen unterhalb der MitarbeiterID
    // Zurückgegeben wird ein MitarbeiterCollection
//  #region 01.08.2024
    {

        $arr = $this->getMitarbeiterUnterMitarbeiter($mitarbeiterID);


        $query = Mitarbeiter::whereIn('id', $arr)
        ->where('ausgeschieden', '0')
        ->with(['beurteilung' => function($q) {
            $q->select('id', 'mitarbeiterid', 'abgeschlossen1', 'abgeschlossen2', 'datum')
                ->orderBy('datum', 'desc');
        }])
        ->orderBy('name', 'asc')
        ->orderBy('vorname', 'asc')

        ->get()
        ->map(function($mitarbeiter) {
            // Nur die letzte Beurteilung für jeden Mitarbeiter auswählen
            $mitarbeiter->beurteilung = $mitarbeiter->beurteilung->first();
            return $mitarbeiter;
        });

        return $query;
    }
//  #endregion


    public function getMitarbeiterArrayUnterBeurteiler2($mitarbeiterID)
    // Liefert ein Array aus Mitarbeiter ID's.
    // Ermittelt werdne die Mitabeiter, über die der angemeldete User Bearbeiter 2 ist.
//  #region 01.08.2024
    {

        $order = 'm1.name'; // Beispiel-Order, passe diese an

        $query = DB::table('users as m1')
            ->select('m3.id as M3_ID')
            ->leftJoin('stellen as st1', 'st1.id', '=', 'm1.stelle') // ST1.id = 12 uebergeordnet = 6
            ->leftJoin('stellen as st2', 'st2.uebergeordnet', '=', 'st1.id') // ST2.id = 13 uebergeordnet = 12
            ->leftJoin('stellen as st3', 'st3.uebergeordnet', '=', 'st2.id') // ST3.id = NULL  => es gibt keine Stelle, die die ID 13 als übergeordnet hat
            ->leftJoin('users as m2', 'm2.stelle', '=', 'st2.id')
            ->leftJoin('users as m3', 'm3.stelle', '=', 'st3.id')
            ->where('m3.ausgeschieden', 0)
            ->where('m1.id', $mitarbeiterID)
            ->orderBy('m1.name', 'asc')
            ->orderBy('m1.vorname', 'asc');

        //echo $query->toSql();
        $results = $query->get();

        //dd($results);
        $arr = [];
        foreach ($results as $item) {
            if (!in_array($item->M3_ID, $arr) && $item->M3_ID != null) {
                $arr[] = $item->M3_ID;
            }
        }

        return $arr;
    }
//  #endregion


    public function getMitarbeiterUnterBeurteiler2($mitarbeiterID)
    {
        // Liefert alle Mitarbeiter ID's.
        // Ermittelt werden die Mitabeiter, über die der angemeldete User Bearbeiter 2 ist.

        $mitarbeiterArr = $this->getMitarbeiterArrayUnterBeurteiler2($mitarbeiterID);

        $order = 'm1.name'; // Beispiel-Order, passe diese an

        // $query = Mitarbeiter::whereIn('id', $mitarbeiterArr)->orderBy('name')->orderBy('vorname')->get();



        $query = Mitarbeiter::whereIn('id', $mitarbeiterArr)
        ->where('ausgeschieden', '0')
        ->with(['beurteilung' => function($q) {
            $q->select('id', 'mitarbeiterid', 'abgeschlossen1', 'abgeschlossen2', 'datum')
              ->orderBy('datum', 'desc');
        }])
        ->orderBy('name')->orderBy('vorname')
        ->get()
        ->map(function($mitarbeiter) {
            // Nur die letzte Beurteilung für jeden Mitarbeiter auswählen
            $mitarbeiter->beurteilung = $mitarbeiter->beurteilung->first();
            return $mitarbeiter;
        });



        return $query;
    }

    public function getMitarbeiterMitOffenenBeurteilungen2($mitarbeiterID)
    {
        $mitarbeiterArr = $this->getMitarbeiterArrayUnterBeurteiler2($mitarbeiterID);

        try {
            $order = ['mm.name', 'mm.vorname'];

            $bedingung_regelbeurteilung_nicht_angefangen = 'bu.abgeschlossen1 = 0';
            $bedingung_nichtausgeschieden = 'm.ausgeschieden = 0';
            $adapter = $this->getAdapter();
            $select = $adapter
                ->select()
                ->from(['m' => 'mitarbeiter'], [])
                ->join(['st1' => 'stellen'], 'st1.uebergeordnet = m.stelle', [])
                ->join(['mm' => 'mitarbeiter'], 'mm.stelle = st1.id')
                ->joinleft(['bu' => 'beurteilung'], 'bu.mitarbeiterid = mm.id', 'id as beurteilungid')
                ->where("m.id = $mitarbeiterID")
                ->where($bedingung_regelbeurteilung_nicht_angefangen)
                ->where($bedingung_nichtausgeschieden)
                ->order($order);
            // echo $select->__toString();
            $rows = $adapter->fetchAll($select);
            return $rows;
        } catch (\Exception $e) {
            echo 'Exception getOffeneMitarbeiterBeurteiler1: ', $e->getMessage(), "\n";
            die();
        }
    }

    public static function getLastBeurteilungID($mitarbeiterId){


        $qu = Beurteilung::select('id')
                ->where('mitarbeiterid', (int)$mitarbeiterId)
                ->orderBy('datum','desc');
        $row = $qu->first();

        $result = $row ? $row->id : -1;

        return $result;

    }

    public function TextB1StatusText(){

        return   $this->beurteilung->abgeschlossen1 === 0 ? 'in Bearbeitung' : 'abgeschlossen';

    }
    public function TextB1Status() : BeurtStatus{
        if ($this->beurteilung){
        return $this->beurteilung->abgeschlossen1 === 0 ? BeurtStatus::edit : BeurtStatus::closed;
        }
        else
            return BeurtStatus::none;
    }

    public function TextB2StatusText(){

        return   $this->beurteilung->abgeschlossen2 == false ? 'in Bearbeitung' : 'abgeschlossen';
    }



    public function TextB2Status(){

        $result = BeurtStatus::none;
        if ($this->beurteilung){
            if ( $this->beurteilung->abgeschlossen1 === 1 ) {
                $result = $this->beurteilung->abgeschlossen2 === 0 ? BeurtStatus::edit : BeurtStatus::closed;
            }
            else
                $result = BeurtStatus::wait;
        }
        return $result ;
    }


    public static function getMitarbeiterUeberStelleByStelle($mStelle){
        // Ergebnis ist der Mitarbeiter der übergeordnetn Stelle
        // Beispiel: Mitarbeiter Maik => Teamleiter Tom => Abteilungsleiter => Andreas
        //           Stelle von Maik wird übergeben
        //           In den B
        // mStelle = variable vom Type $Stelle.
        // Über der Stelle des aktuellen Mitarbeiters ist der Beurteiler 1
        return Mitarbeiter::where('stelle', $mStelle->uebergeordnet)->first();
    }


    // NEU 12.07.2024
    public function getMitarbeiterohneBeurteilungAlsBeurteiler2asArray($mitarbeiterID, $datumvon, $datumbis)
    {
        try {
            // Vorschlag ******************************************************
            $mitarbeiterID = [79]; // frye // Beispiel-ID, setze hier die tatsächliche ID
            //$mitarbeiterID = 21 ; // Blömer => Stelle=200 // Beispiel-ID, setze hier die tatsächliche ID

            $bedingung_nichtausgeschieden = ['m1.ausgeschieden' => 0]; // Beispiel-Bedingung, passe diese an
            $order = 'm1.name'; // Beispiel-Order, passe diese an
            echo '<p>';
            echo date('Y-m-d H:i:s');
            echo '</p>';

            $query = DB::table('users as m1')
                ->select('m1.id as M1-ID', 'st1.id as ST1-ID', 'm2.id as M2-ID', 'st2.id as ST2-ID', 'm3.id as M3-ID', 'st3.id as ST3-ID', 'm4.id as M4-ID', 'st4.id as ST4-ID', 'm5.id as M5-ID', 'st5.id as ST5-ID', 'm6.id as M6-ID', 'st6.id as ST6-ID')
                ->leftJoin('stellen as st1', 'st1.id', '=', 'm1.stelle') // ST1.id = 12 uebergeordnet = 6
                ->leftJoin('stellen as st2', 'st2.uebergeordnet', '=', 'st1.id') // ST2.id = 13 uebergeordnet = 12
                ->leftJoin('stellen as st3', 'st3.uebergeordnet', '=', 'st2.id') // ST3.id = NULL  => es gibt keine Stelle, die die ID 13 als übergeordnet hat
                ->leftJoin('stellen as st4', 'st4.uebergeordnet', '=', 'st3.id')
                ->leftJoin('stellen as st5', 'st5.uebergeordnet', '=', 'st4.id')
                ->leftJoin('stellen as st6', 'st6.uebergeordnet', '=', 'st5.id')
                ->leftJoin('stellen as st7', 'st7.uebergeordnet', '=', 'st6.id')
                ->leftJoin('users as m2', 'm2.stelle', '=', 'st2.id')
                ->leftJoin('users as m3', 'm3.stelle', '=', 'st3.id')
                ->leftJoin('users as m4', 'm4.stelle', '=', 'st4.id')
                ->leftJoin('users as m5', 'm2.stelle', '=', 'st5.id')
                ->leftJoin('users as m6', 'm2.stelle', '=', 'st6.id')
                ->where('m1.id', $mitarbeiterID);
            echo $query->toSql();

            $results = $query->get();

            $arr = [];
            dd($results);
            foreach ($results as $item) {
                if (!in_array($item['m2'], $arr) && $item['m2'] != null) {
                    $arr[] = $item['m2'];
                }
                if (!in_array($item['m3'], $arr) && $item['m3'] != null) {
                    $arr[] = $item['m3'];
                }
                if (!in_array($item['m4'], $arr) && $item['m4'] != null) {
                    $arr[] = $item['m4'];
                }
                if (!in_array($item['m5'], $arr) && $item['m5'] != null) {
                    $arr[] = $item['m5'];
                }
                if (!in_array($item['m6'], $arr) && $item['m6'] != null) {
                    $arr[] = $item['m6'];
                }
            }
            dd($arr);
            asort($arr);

            $mitarbeiter = implode(', ', $arr) . "\n";

            $order = ['m.name', 'm.vorname'];

            $bedingung_zeitraum = sprintf("b.datum between '%s' and '%s'", $this->_strToDate($datumvon), $this->_strToDate($datumbis));
            $bedingung_mitarbeiter = sprintf('b.mitarbeiterid in ( %s ) ', $mitarbeiter);
            $bedingung_veraltet = 'b.veraltet = 0';

            $adapter = $this->getAdapter();
            $select = $adapter
                ->select()
                ->from(['b' => 'beurteilung'], ['b.id as bid'])
                ->joinleft(['m' => 'mitarbeiter'], 'm.id = b.mitarbeiterid', null) // Mitarbeiter für die Sortierung
                ->where($bedingung_zeitraum)
                ->where($bedingung_mitarbeiter)
                ->where($bedingung_veraltet)
                ->order($order);

            $rows = $adapter->fetchAll($select);
            $arr = [];
            foreach ($rows as $item) {
                $arr[] = $item['bid'];
            }

            return $arr;
        } catch (\Exception $e) {
            echo 'Exception getMitarbeiterohneBeurteilungAlsBeurteiler2: ', $e->getMessage(), "\n";
            die();
        }
    }


    public static function getEbenenUnterMitarbeiter($mitarbeiterID){

        $arr = Mitarbeiter::getMitarbeiterUnterMitarbeiter($mitarbeiterID );

        $ebenen = DB::table('users')
            ->whereIn('users.id', $arr) // Filter auf die Mitarbeiter-IDs
            ->join('stellen', 'users.stelle', '=', 'stellen.id') // Verknüpfen der "stellen" Tabelle
            ->select('stellen.ebene') // Auswahl der Ebenen
            ->distinct() // Duplikate entfernen
            ->orderBy('ebene')
            ->pluck('ebene'); // Die Spalte "ebene" extrahieren
        return $ebenen;
    }

    public static function getStellenUnterMitarbeiter($mitarbeiterID){

        $arr = Mitarbeiter::getMitarbeiterUnterMitarbeiter($mitarbeiterID );

        $stellen = DB::table('users')
            ->whereIn('users.id', $arr) // Filter auf die Mitarbeiter-IDs
            ->join('stellen', 'users.stelle', '=', 'stellen.id') // Verknüpfen der "stellen" Tabelle
            ->select('stellen.id', 'stellen.bezeichnung') // Auswahl der Ebenen
            ->distinct() // Duplikate entfernen
            ->orderBy('bezeichnung')
            ->get();
            //->pluck('id', 'bezeichnung'); // Die Spalte "ebene" extrahieren
        return $stellen;
    }

    public static function getBeurteilerUnterMitarbeiter($mitarbeiterID){

        $arr = Mitarbeiter::getMitarbeiterUnterMitarbeiter($mitarbeiterID );

        $beurteiler = DB::table('users')
            ->whereIn('users.id', $arr) // Filter auf die Mitarbeiter-IDs
            ->where('stellen.fuehrungskompetenz', 1)
            ->join('stellen', 'users.stelle', '=', 'stellen.id') // Verknüpfen der "stellen" Tabelle
            ->select('users.id', 'users.name') // Auswahl der Ebenen
            ->orderBy('users.name')
            ->get();

        return $beurteiler;
    }

    public static function getAnstellungUnterMitarbeiter($mitarbeiterID){

        $arr = Mitarbeiter::getMitarbeiterUnterMitarbeiter($mitarbeiterID );

        $anstellung = DB::table('users')
            ->whereIn('users.id', $arr) // Filter auf die Mitarbeiter-IDs
            ->select('users.anstellung') // Auswahl der Ebenen
            ->distinct()
            ->get();

        return $anstellung;
    }

    public static function getBesoldungUnterMitarbeiter($mitarbeiterID){

        $arr = Mitarbeiter::getMitarbeiterUnterMitarbeiter($mitarbeiterID );

        $besoldung = DB::table('users')
            ->whereIn('users.id', $arr) // Filter auf die Mitarbeiter-IDs
            ->select('users.besoldung') // Auswahl der Ebenen
            ->distinct()
            ->orderBy('besoldung')
            ->get();

        return $besoldung;
    }

    public static function getBeurteilterUnterMitarbeiter($mitarbeiterID){

        $arr = Mitarbeiter::getMitarbeiterUnterMitarbeiter($mitarbeiterID );

        $besoldung = DB::table('users')
            ->whereIn('users.id', $arr) // Filter auf die Mitarbeiter-IDs
            ->select('users.id', 'users.name') // Auswahl der Ebenen
            ->orderBy('name')
            ->get();

        return $besoldung;
    }



}

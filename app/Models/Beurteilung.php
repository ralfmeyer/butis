<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bdetails;
use App\Models\Kriterien;
use App\Models\Mitarbeiter;
use App\Models\BeurtStatus;
use Illuminate\Support\Facades\Log;


class Beurteilung extends Model
{
    use HasFactory;

    protected $table = 'beurteilung';

    protected $fillable = [
        'id',
        'mitarbeiterid',
        'mitarbeiterfuehrung',
        'beurteiler1',
        'beurteiler2',
        'stelleid',
        'stellebeurteiler1',
        'stellebeurteiler2',
        'stellebeurteilter',
        'datum',
        'abgabedatum',
        'bemerkung1',
        'bemerkung2',
        'gesamtnote1',
        'gesamtnote2',
        'gesamtnote1begruendung',
        'gesamtnote2begruendung',
        'regelbeurteilung',
        'beurteilungszeitpunkt',
        'abgeschlossen1',
        'abgeschlossen2',
        'veraltet',
        'zusatz1',
        'zusatz2',
        'besoldung',
        'zeitraumvon',
        'zeitraumbis',
        'aufgabenbereich',
        'anlass',
        'ledit1',
        'ledit2',
        'nr_gesetzt',
        'anstellung',
        'teilzeit',
        'amt',
        'geeignet1',
        'geeignet2',
        'version'
    ];

    protected $attributes = [
        'id' => null,
        'mitarbeiterid' => '-1',
        'mitarbeiterfuehrung' => 'false',
        'beurteiler1' => 0,
        'beurteiler2' => 0,
        'stelleid' => 0,
        'stellebeurteiler1' => '',
        'stellebeurteiler2' => '',
        'stellebeurteilter' => '',
        'datum' => '',
        'abgabedatum' => '',
        'bemerkung1' => '',
        'bemerkung2' => '',
        'gesamtnote1' => 0,
        'gesamtnote2' => 0,
        'gesamtnote1begruendung' => '',
        'gesamtnote2begruendung' => '',
        'regelbeurteilung' => 0,
        'beurteilungszeitpunkt' => 0,
        'abgeschlossen1' => 0,
        'abgeschlossen2' => 0,
        'veraltet' => 0,
        'zusatz1' => '',
        'zusatz2' => '',
        'besoldung' => '',
        'zeitraumvon' => '',
        'zeitraumbis' => '',
        'aufgabenbereich' => '',
        'anlass' => '',
        'ledit1' => null,
        'ledit2' => null,
        'nr_gesetzt' => 0,
        'anstellung' => 1,
        'teilzeit' => 0,
        'amt' => '',
        'geeignet1' => 0,
        'geeignet2' => 0,
        'version' => 2,
    ];

    public function __construct(){
        /*
        $backtrace = debug_backtrace();
        if (isset($backtrace[1])) {
            Log::info ('Die Funktion ',[ $backtrace[1]['function'], $backtrace[0]['function']]);
        } else {
            Log::info('Diese Funktion beurteilung.__construct wurde direkt aufgerufen.');
        }
            */
    }


    public function bdetails()
    {
        return $this->hasMany(Bdetails::class, 'beurteilungid');
    }

    public function mMitarbeiter(){
        return $this->belongsTo(Mitarbeiter::class, 'mitarbeiterid', 'id');
    }

    /*
    public function readBeurteilungenMitarbeiter($id)
    {

        $adapter = $this->getAdapter();
        $select = $adapter->select()
            ->from(array('b' => 'beurteilung'), array('*'))
            ->joinleft(array('mb1' => 'mitarbeiter'), 'mb1.id = b.beurteiler1', array('mb1.name as beurteiler1name', 'mb1.vorname as beurteiler1vorname'))
            ->joinleft(array('mb2' => 'mitarbeiter'), 'mb2.id = b.beurteiler2', array('mb2.name as beurteiler2name', 'mb2.vorname as beurteiler2vorname'))
            ->where("b.mitarbeiterid = $id")
            ->order('b.datum desc');
        // echo $select->__toString() ;
        $rows = $adapter->fetchAll($select);

        return $rows;
    }
    */


    public function getAktiveVonBeurteiler1($mitbeiterArr){
    // Liefert eine BeurteilungCollection aller aktiven Beurteilungen der übergebenen MitarbeiterID's
//  #region 01.08.2024

            $query = $this->whereIn('mitarbeiterid', $mitbeiterArr)
                    ->where('abgeschlossen1', 0);

            $result = $query->get();
            return $result;
        }
//  #endregion


    public function getAktiveVonBeurteiler2($mitbeiterArr){
    // Liefert eine BeurteilungCollection aller aktiven Beurteilungen der übergebenen MitarbeiterID's
//  #region 01.08.2024
        $result = $this->whereIn('mitarbeiterid', $mitbeiterArr)
                ->where('abgeschlossen1', 1)
                ->where('abgeschlossen2', 0)
                ->get();
        return $result;
    }
//  #endregion





    public function updateBeurteilungenMitarbeiter_als_erledigt($mitid, $beurteilungid)
    {
        $data = array('veraltet' => new Zend_Db_Expr('true'));
        $where =  "mitarbeiterid = $mitid and id <> $beurteilungid";


        try {
            $this->update($data, $where);
        } catch (Exception $e) {
            echo 'Exception abgefangen, updateBeurteilungenMitarbeiter_als_erledigt: ',  $e->getMessage(), "\n";
            die;
        }
    }

    public function updateBeurteilungAbgabedatum($mitid, $abgabedatum)
    {
        $data = array('abgabedatum' => $abgabedatum);
        $where =  "mitarbeiterid = $mitid and veraltet = false";


        try {
            $this->update($data, $where);
        } catch (Exception $e) {
            echo 'Exception abgefangen, updateBeurteilungAbgabedatum: ',  $e->getMessage(), "\n";
            die;
        }
    }

    public function getBeurteiler($beurteiler1ID, $beurteiler2ID)
    {
        $result = array();
        $adapter = $this->getAdapter();

        $select = $adapter->select()
            ->from(array('m' => 'mitarbeiter'),     array('id', 'name', 'vorname', 'anrede'))
            ->joinleft(array('st1' => 'stellen'), 'st1.id = m.stelle',                 array('bezeichnung as stelle'))
            ->where("m.id = $beurteiler1ID");

        $rows = $adapter->fetchRow($select);
        $result["beurteiler1"] = $beurteiler1ID;
        $result["b1name"] = $rows["name"];
        $result["b1vorname"] = $rows["vorname"];
        $result["b1anrede"] = $rows["anrede"];
        $result["b1stelle"] = $rows["stelle"];

        $select = $adapter->select()
            ->from(array('m' => 'mitarbeiter'),     array('id', 'name', 'vorname', 'anrede'))
            ->joinleft(array('st1' => 'stellen'), 'st1.id = m.stelle',                 array('bezeichnung as stelle'))
            ->where("m.id = $beurteiler2ID");
        $rows = $adapter->fetchRow($select);

        $result["beurteiler2"] = $beurteiler1ID;
        $result["b2name"] = $rows["name"];
        $result["b2vorname"] = $rows["vorname"];
        $result["b2anrede"] = $rows["anrede"];
        $result["b2stelle"] = $rows["stelle"];

        return $result;
    }

    public function getBeurteilungenFromBeurteiler2($beurteilerID)
    {
        Log::info("Beurteilung.getBeurteilungenFromBeurteiler2() anfang", [ $beurteilerID]);
        $cBeurteilung = null;
        if (isset($beurteilerID) && is_numeric($beurteilerID)) {
            $cBeurteilung = new cBeurteilung($beurteilerID);
        } else {
            $cBeurteilung = new cBeurteilung(-1);
        }
        Log::info("Beurteilung.getBeurteilungenFromBeurteiler2() ende ::: cBeurteilung is Null=", [ isset($cBeurteilung)]);
        return $cBeurteilung;
    }


    public static function getBeurteilungByMitarbeiterID($mitarbeiterID){

        return Beurteilung::select('id')
                ->where('mitarbeiterid', $mitarbeiterID)
                ->orderBy('datum','desc')
                ->first();
    }

    public function B1Status() : BeurtStatus{

        return $this->abgeschlossen1 === 0 ? BeurtStatus::edit : BeurtStatus::closed;
    }

    public function B2Status(){

        if ( $this->abgeschlossen1 === 1 ) {
            $result = $this->abgeschlossen2 === 0 ? BeurtStatus::edit : BeurtStatus::closed;
        }
        else{
            $result = BeurtStatus::wait;
        }

        return $result ;
    }

}


class cBeurteilung
{
    public $beurteilter;
    public $beurteiler1;
    public $beurteiler2;
    public $beurteilung;
    public $bmerkmale;
    public $data;
    public $dataCount;
    public $stelle;
    public $isOK;

    public function __construct($beurteilerID)
    {


        $mBeurteilung = new Beurteilung();

        Log::info('beurteilerID', [ $beurteilerID ] );

        $this->beurteilung = $mBeurteilung->find($beurteilungId);

        if ($this->beurteilung) {
            Log::info('Beurteilung gefunden! ');

            $mMitarbeiter = new Mitarbeiter();
            $this->beurteilter = $mMitarbeiter->getEntry(array($this->beurteilung["mitarbeiterid"]));

            $mStelle = new Stelle();
            $this->stelle = $mStelle->getEntry(array($this->beurteilter->stelle));

            $this->beurteiler1 = $mMitarbeiter->getEntry(array($this->beurteilung["beurteiler1"]));
            $this->beurteiler2 = $mMitarbeiter->getEntry(array($this->beurteilung["beurteiler2"]));

            $mMerkmale = new Kriterien();
            $mBdetails = new Bdetails();

            $merkmale = $mMerkmale->getListe('art = 0', 'nummer');

            foreach ($merkmale as $merkmal) {
                //				echo "ID : ". $merkmal->id."  " ;
                $item                                                     = $merkmal->id;
                $this->data[$merkmal->id]['errorbeurteiler1bemerkung']    = false;
                $bdetail                                                 = $mBdetails->getDetail($beurteilungId, $merkmal->id);
                $this->data[$merkmal->id]['id']                         = $merkmal->id;
                $this->data[$merkmal->id]['bereich']                     = $merkmal->bereich;
                $this->data[$merkmal->id]['nummer']                     = $merkmal->nummer;
                $this->data[$merkmal->id]['ueberschrift']                 = $merkmal->ueberschrift;
                $this->data[$merkmal->id]['text1']                         = $merkmal->text1;
                $this->data[$merkmal->id]['text2']                         = $merkmal->text2;
                $this->data[$merkmal->id]['text3']                         = $merkmal->text3;
                $this->data[$merkmal->id]['text4']                         = $merkmal->text4;
                $this->data[$merkmal->id]['text5']                         = $merkmal->text5;
                $this->data[$merkmal->id]['hinweistextallgemein']         = $merkmal->hinweistextallgemein;
                $this->data[$merkmal->id]['hinweistext1']                 = $merkmal->hinweistext1;

                // Note Beurteiler 1
                $name                                                     = "beurteiler1note$item";

                $this->data[$merkmal->id]['beurteiler1note']             = $bdetail["beurteiler1note"];

                // Note Beurteiler 2
                $this->data[$merkmal->id]['beurteiler2note']             = $bdetail["beurteiler2note"];

                // Note Bemerkung Beurteiler 1
                $this->data[$merkmal->id]['beurteiler1bemerkung']         = $bdetail["beurteiler1bemerkung"];

                // Note Bemerkung Beurteiler 2
                $this->data[$merkmal->id]['beurteiler2bemerkung']         = $bdetail["beurteiler2bemerkung"];
                $note = $this->data[$merkmal->id]['beurteiler2note'];
                $doUnset = false;
                // echo "F.Kompetenz: ".$this->stelle->fuehrungskompetenz." Stelle.ID".$this->stelle->id." Beurteilter.ID".$this->beurteilter->id." Merkmal.Nr:".(double)$this->data[$merkmal->id]["nummer"]."<br>";
                if ($this->stelle->fuehrungskompetenz == false && $this->data[$merkmal->id]["nummer"] >= 4) {
                    $doUnset = true;
                    //echo $this->beurteilter->id."  Nummer > 4: ".$this->data[$merkmal->id]["nummer"]."<br>";
                }
                if ($note == 3 || $note == 4) {
                    $doUnset = true;
                }
                if ($doUnset) {
                    unset($this->data[$merkmal->id]);
                }
            }
            $this->dataCount = count($this->data);
            $this->isOK = ($this->dataCount > 0) and ($this->beurteilung->gesamtnote2 != 3 and $this->beurteilung->gesamtnote2 != 4);
        }
        else
        {
            Log::error('Beurteilung nicht gefunden! ' );
        }
    }
}

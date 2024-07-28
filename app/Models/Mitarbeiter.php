<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Mitarbeiter extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = ['id', 'personalnr', 'anrede', 'vorname', 'name', 'gebdatum', 'stelle', 'kennwort', 'anstellung', 'besoldung', 'lregelbeurteilung', 'nbeurteilung', 'amt', 'bemerkung', 'email', 'vertragsende', 'teilzeit', 'benachrichtigt', 'abgabedatum'];

    public function stelleBezeichnung()
    {
        return $this->belongsTo(Stelle::class, 'stelle');
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
      public function getFaelligeMitarbeiterBeurteiler1( $mitarbeiterID )
      {
            try {
     
                $order = array ( 'mm.name', 'mm.vorname' ) ;
     
     
                $vorlauf = 60 ;//11.07.2024 #################### Application_Model_Config::getAlle( 'vorlauf' ) ;
                if ($vorlauf == "")
                    $vorlauf = 60 ;
                
                $bedingung_zeiraum = "(( datediff( mm.nbeurteilung, curdate() ) <= ? ) or mm.nbeurteilung is null)" ;
                $bedingung_regelbeurteilung_nicht_angefangen = "not exists( select id from beurteilung where mitarbeiterid = mm.id and ( abgeschlossen1 = 0 or ( abgeschlossen1 = 1 and abgeschlossen2 = 0 ) ) )" ;
                $bedingung_nichtausgeschieden = "(mm.ausgeschieden = 0) " ;


                $query = DB::table('users as m')->select('m.*', 'st1.*')
                ->leftJoin('stellen as st1', 'st1.id', '=', 'm.stelle')         // ST1.id = 12 
                ->leftJoin('users as mm', 'mm.stelle', '=' , 'st1.id')
                ->where('m.id', $mitarbeiterID)
                ->where( $bedingung_nichtausgeschieden )
                ->whereRaw($bedingung_zeiraum, [ $vorlauf ] )
                ->orderBy( $order )
                #->raw( $bedingung_regelbeurteilung_nicht_angefangen )  // keine angefangene Beurteilung haben und
                                 // nicht ausgeschieden sind.
                ;


                //$sql = $query->toSql ();
                //echo $sql;


                $rows = $query->get();
                dd($rows);


                
     /* ALTe original Bedingung
                // Selektiere alle Mitarbieter die zu Beurteiler 1 gehören und
                $select = $adapter->select()
                                                ->from( array( 'm' => 'mitarbeiter' ), array( ) )
                                                ->join( array( 'st1' => 'stellen'), 'st1.uebergeordnet = m.stelle', array())
                                                ->join( array( 'mm' => 'mitarbeiter'), 'mm.stelle = st1.id' )
                                                ->where( "m.id = $mitarbeiterID" )
                                                ->where( $bedingung_zeiraum )                            // innerhalb des Zeitraums liegen und
                                                ->where( $bedingung_regelbeurteilung_nicht_angefangen )  // keine angefangene Beurteilung haben und
                                                ->where( $bedingung_nichtausgeschieden )                 // nicht ausgeschieden sind.
                                                ->order( $order ) ;
     
                $rows = $adapter->fetchAll( $select ) ;
    */
                return $rows;
                    
     
            } catch (Exception $e) {
                echo 'Exception getFaelligeMitarbeiterBeurteiler1: ',  $e->getMessage(), "\n";
                die;
            }
      }
     

    /* NEU 12.07.2024
    public function getOffeneMitarbeiterBeurteiler1( $params )
    {
          try {
              if (array_key_exists( 'mitarbeiter', $params ) )
                  $mitarbeiterID = $params['mitarbeiter'] ;

              $order = array ( 'mm.name', 'mm.vorname' ) ;
              //if (array_key_exists( 'tabelle', $params )  )
              //	$order = array ( Application_Model_Config::getMitarbeiter ( $params['tabelle'] ) ) ;

              $bedingung_regelbeurteilung_nicht_angefangen = "bu.abgeschlossen1 = 0" ;
              $bedingung_nichtausgeschieden = "m.ausgeschieden = 0" ;
              $adapter = $this->getAdapter();
              $select = $adapter->select()
                                              ->from( array( 'm' => 'mitarbeiter' ), array( ) )
                                              ->join( array( 'st1' => 'stellen'), 'st1.uebergeordnet = m.stelle', array())
                                              ->join( array( 'mm' => 'mitarbeiter'), 'mm.stelle = st1.id' )
                                              ->joinleft( array( 'bu' => 'beurteilung'), 'bu.mitarbeiterid = mm.id', 'id as beurteilungid' )
                                              ->where( "m.id = $mitarbeiterID" )
                                              ->where( $bedingung_regelbeurteilung_nicht_angefangen )
                                              ->where( $bedingung_nichtausgeschieden )
                                              ->order( $order ) ;
              // echo $select->__toString();
              $rows = $adapter->fetchAll( $select ) ;
              return $rows;
          } catch (Exception $e) {
              echo 'Exception getOffeneMitarbeiterBeurteiler1: ',  $e->getMessage(), "\n";
              die;
          }
    }
NEU */

    /* NEU 12.07.2024
    public function getOffeneMitarbeiterBeurteiler2( $params )
    {
          try {
              // Bedingungen warum ein Mitarbeiter bei Beurteiler 2 als Fällig angezeigt wird.
              // - ich bin beurteiler 2 eines Mitarbeiters
              // - die beurteilung wurde von beurteiler 1 bereits abgeschlossen aber noch nicht von Beurteiler 2
              if (array_key_exists( 'mitarbeiter', $params ) )
                  $mitarbeiterID = $params['mitarbeiter'] ;

              $order = array ( 'm.name', 'm.vorname' ) ;
              //if (array_key_exists( 'tabelle', $params )  )
              //	$order = array ( Application_Model_Config::getMitarbeiter ( $params['tabelle'] ) ) ;

              $bedingung_beuteiler1_abgeschlossen = "b.abgeschlossen1 = 1 and b.abgeschlossen2 = 0" ;
              $bedingung_nichtausgeschieden = "m.ausgeschieden = 0" ;

              $adapter = $this->getAdapter();
              $select = $adapter->select()
                                              ->from( array( 'm' => 'mitarbeiter' ), null )
                                              ->join( array( 'st1' => 'stellen'), 'st1.id = m.stelle', null )
                                              ->join( array( 'st2' => 'stellen'), 'st2.uebergeordnet = st1.id', null )
                                              ->join( array( 'st3' => 'stellen'), 'st3.uebergeordnet = st2.id', null  )
                                              ->join( array( 'm2' => 'mitarbeiter'), 'm2.stelle = st3.id', array( '*' ) )
                                              ->join( array( 'b' => 'beurteilung'),  'b.mitarbeiterid = m2.id', array( 'id as beurteilungid' ) )
                                              ->where( "m.id = $mitarbeiterID" )
                                              ->where( $bedingung_beuteiler1_abgeschlossen )
                                              ->order( $order )
                                              ;
  $rows = $adapter->fetchAll( $select ) ;

              return $rows;
          } catch (Exception $e) {
              echo 'Exception getOffeneMitarbeiterBeurteiler2: ',  $e->getMessage(), "\n";
              die;
          }
    }
NEU */

    /* NEU 12.07.2024

      // Mitarbeiter ohne Beurteilung
      // Wird auf der Indexseite Tab1 unten dargestellt.
      public function getMitarbeiterohneBeurteilungAlsBeurteiler1( $params )
      {
          try{
              if (array_key_exists( 'mitarbeiter', $params ) )
                  $mitarbeiterID = $params['mitarbeiter'] ;

              $order = array ( 'm2.name', 'm2.vorname' ) ;


              $bedingung_nichtausgeschieden = "m2.ausgeschieden = 0" ;

              $vorlauf = Application_Model_Config::getAlle( 'vorlauf' ) ;
              if ($vorlauf == "")
                  $vorlauf = 60 ;
              $bedingung_zeiraum = "not (( datediff( m2.nbeurteilung, curdate() ) <= $vorlauf ) or m2.nbeurteilung is null)" ;
              $adapter = $this->getAdapter();
              $select = $adapter->select()
                                              ->from( array( 'm1' => 'mitarbeiter' ), null )
                                              ->joinleft( array( 'st1' => 'stellen'), 'st1.id = m1.stelle', null )
                                              ->joinleft( array( 'st2' => 'stellen'), 'st2.uebergeordnet = st1.id', null )
                                              ->joinleft( array( 'm2' => 'mitarbeiter'), 'm2.stelle = st2.id', array( '*' ) )
                                              ->joinleft( array( 'b' => 'beurteilung'),  'b.mitarbeiterid = m2.id and b.veraltet = 0', array('b.id as bid', 'abgeschlossen1', 'abgeschlossen2' ) )
                                              ->where( "m1.id = $mitarbeiterID" )

                                              ->where( $bedingung_nichtausgeschieden )

                                              ->order( $order )
                                              ;


        $rows = $adapter->fetchAll( $select ) ;

            return $rows;
          } catch (Exception $e) {
              echo 'Exception getMitarbeiterohneBeurteilungAlsBeurteiler1: ',  $e->getMessage(), "\n";
              die;
          }
      }

NEU */

    /* NEU 12.07.2024
      // Mitarbeiter ohne Beurteilung
      // Wird auf der Indexseite unten dargestellt.
      // Angelegt in Verson 1.0
      public function getMitarbeiterohneBeurteilungAlsBeurteiler2( $params )
      {
          try{
              if (array_key_exists( 'mitarbeiter', $params ) )
                  $mitarbeiterID = $params['mitarbeiter'] ;

              $order = array ( 'm2.name', 'm2.vorname' ) ;


              $bedingung_nichtausgeschieden = "m2.ausgeschieden = 0" ;

              $vorlauf = Application_Model_Config::getAlle( 'vorlauf' ) ;
              if ($vorlauf == "")
                  $vorlauf = 60 ;

              $bedingung_zeiraum = "not (( datediff( m2.nbeurteilung, curdate() ) <= $vorlauf ) or m2.nbeurteilung is null)" ;
              $adapter = $this->getAdapter();
              $select = $adapter->select()
                                              ->from( array( 'm1' => 'mitarbeiter' ), null )
                                              ->joinleft( array( 'st1' => 'stellen'), 'st1.id = m1.stelle', null )			// Stelle des aufrufenden
                                              ->joinleft( array( 'st2' => 'stellen'), 'st2.uebergeordnet = st1.id', null )	// Stelle des 1. untergeordneten ( ich bin beurteiler 1 darüber
                                              ->joinleft( array( 'st3' => 'stellen'), 'st3.uebergeordnet = st2.id', null )	// Stelle des 2. untergeordneten ( ich bin beurteiler 2 darüber
                                              ->joinleft( array( 'm2' => 'mitarbeiter'), 'm2.stelle = st3.id', array( '*' ) )
                                              ->joinleft( array( 'b' => 'beurteilung'),  'b.mitarbeiterid = m2.id and b.veraltet = 0 ', array('b.id as bid', 'abgeschlossen1', 'abgeschlossen2' ) )
                                              ->where( "m1.id = $mitarbeiterID" )
                                              ->where( $bedingung_nichtausgeschieden )
                                              ->order( $order )
                                              ;



                $rows = $adapter->fetchAll( $select ) ;

            return $rows;
          } catch (Exception $e) {
              echo 'Exception getMitarbeiterohneBeurteilungAlsBeurteiler2: ',  $e->getMessage(), "\n";
              die;
          }
      }
NEU */

    // NEU 12.07.2024
    public function getMitarbeiterohneBeurteilungAlsBeurteiler2asArray($mitarbeiterID, $datumvon, $datumbis)
    {
        try {
            // Vorschlag ******************************************************
            $mitarbeiterID = [ 79 ]; // frye // Beispiel-ID, setze hier die tatsächliche ID
            //$mitarbeiterID = 21 ; // Blömer => Stelle=200 // Beispiel-ID, setze hier die tatsächliche ID

            $bedingung_nichtausgeschieden = ['m1.ausgeschieden' => 0];  // Beispiel-Bedingung, passe diese an
            $order = 'm1.name';  // Beispiel-Order, passe diese an
echo "<p>";
            echo date('Y-m-d H:i:s');
echo "</p>";

            $query = DB::table('users as m1')->select('m1.id as M1-ID', 'st1.id as ST1-ID','m2.id as M2-ID', 'st2.id as ST2-ID', 'm3.id as M3-ID', 'st3.id as ST3-ID', 'm4.id as M4-ID', 'st4.id as ST4-ID','m5.id as M5-ID', 'st5.id as ST5-ID', 'm6.id as M6-ID', 'st6.id as ST6-ID')
                ->leftJoin('stellen as st1', 'st1.id', '=', 'm1.stelle')         // ST1.id = 12 uebergeordnet = 6
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
                //->toSql();
            

            /*
             * ->leftJoin('beurteilung as b', function($join) {
             *     $join->on('b.mitarbeiterid', '=', 'm2.id')
             *          ->where('b.veraltet', '=', 0);
             * })
             * ->where('m1.id', $mitarbeiterID)
             * ->where($bedingung_nichtausgeschieden)
             * ->select('m2.*', 'b.id as bid', 'b.abgeschlossen1', 'b.abgeschlossen2')
             * ->orderBy($order)
             * ->get();
             */
            // Vorschlag ENDE ******************************************************
/*
            $adapter = $this->getAdapter();
            $select = $adapter
                ->select()
                ->from(['m1' => 'mitarbeiter'], null)
                ->joinleft(['st1' => 'stellen'], 'st1.id = m1.stelle', null)  // Ebene 0 => aktuelle Ebene
                ->joinleft(['st2' => 'stellen'], 'st2.uebergeordnet = st1.id', null)  // Ebene 1
                ->joinleft(['st3' => 'stellen'], 'st3.uebergeordnet = st2.id', null)  // Ebene 2
                ->joinleft(['st4' => 'stellen'], 'st4.uebergeordnet = st3.id', null)  // Ebene 3
                ->joinleft(['st5' => 'stellen'], 'st5.uebergeordnet = st4.id', null)  // Ebene 4
                ->joinleft(['st6' => 'stellen'], 'st6.uebergeordnet = st5.id', null)  // Ebene 5
                ->joinleft(['st7' => 'stellen'], 'st7.uebergeordnet = st6.id', null)  // Ebene 6
                ->joinleft(['m2' => 'mitarbeiter'], 'm2.stelle = st2.id', ['m2.id as m2'])  // Mitarbeiter Ebene 2
                ->joinleft(['m3' => 'mitarbeiter'], 'm3.stelle = st3.id', ['m3.id as m3'])  // Mitarbeiter Ebene 3
                ->joinleft(['m4' => 'mitarbeiter'], 'm4.stelle = st4.id', ['m4.id as m4'])  // Mitarbeiter Ebene 4
                ->joinleft(['m5' => 'mitarbeiter'], 'm5.stelle = st5.id', ['m5.id as m5'])  // Mitarbeiter Ebene 5
                ->joinleft(['m6' => 'mitarbeiter'], 'm6.stelle = st6.id', ['m6.id as m6'])  // Mitarbeiter Ebene 6
                ->where("m1.id = $mitarbeiterID");
*/
            //$rows = $adapter->fetchAll($select);
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
                ->joinleft(['m' => 'mitarbeiter'], 'm.id = b.mitarbeiterid', null)  // Mitarbeiter für die Sortierung
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
        } catch (Exception $e) {
            echo 'Exception getMitarbeiterohneBeurteilungAlsBeurteiler2: ', $e->getMessage(), "\n";
            die();
        }
    }

    // NEU

    /* NEU 12.07.2024
    public function getMitarbeiterUnterBeurteiler( $params )
    {
          try {
              if (array_key_exists( 'mitarbeiter', $params ) )
                  $mitarbeiterID = $params['mitarbeiter'] ;

              $order = array ( 'm.name', 'm.vorname' ) ;

              $mitarbeiter = implode(",", $this->getListeBeurteilteAsArray( $mitarbeiterID ));

                  if ($mitarbeiter == "")
                  $mitarbeiter = "-1";

              $bedingung = sprintf("m.id in (%s)", $mitarbeiter );

              $adapter = $this->getAdapter();

  $command = <<< MyString
  SELECT `m`.*, `bu`.`id` AS `bid`, `bu`.`abgeschlossen1`, `bu`.`abgeschlossen2` FROM `mitarbeiter` AS `m`
   LEFT JOIN `beurteilung` AS `bu` ON bu.id = (select id from beurteilung where mitarbeiterid = m.id order by Datum desc limit 0,1)
  MyString;

              $command .= sprintf("WHERE (m.id in (%s)) ORDER BY `m`.`name` ASC, `m`.`vorname` ASC", $mitarbeiter);

              $rows = $adapter->fetchAll( $command ) ;

              return $rows;
          } catch (Exception $e) {
              echo "sql: ".$command."\n";
              echo 'Exception getMitarbeiterUnterBeurteiler: ',  $e->getMessage(), "\n";

              die;
          }
    }
NEU */

    /* NEU 12.07.2024

    // Wird gebraucht in Controller:Beurteilung Action:Create, Change, Show und Print
    // Zum übergebenen Mitarbeiter wird der Beurteiler 1 und 2 zurückgegeben.
    // Angelegt in Version 1.0
    public function getBeurteiler( $mitarbeiterID )
    {

               $adapter = $this->getAdapter();
            $select = $adapter->select()
                ->from( array( 'm' => 'mitarbeiter' ), 	array( 'id', 'stelle' ) )
                ->joinleft( array( 'st1' => 'stellen'), 'st1.id = m.stelle', 				array( ))
                ->joinleft( array( 'mm1' => 'mitarbeiter'), 'mm1.stelle = st1.uebergeordnet and mm1.ausgeschieden = false',	array( 'id as beurteiler1', 'name as b1name', 'vorname as b1vorname', 'anrede as b1anrede', 'email as b1email'))
                ->joinleft( array( 'st2' => 'stellen'), 	'st2.id = mm1.stelle', 				array( 'bezeichnung as b1stelle'))
                ->joinleft( array( 'mm2' => 'mitarbeiter'), 'mm2.stelle = st2.uebergeordnet and mm2.ausgeschieden = false and mm2.stelle > 0',	array( 'id as beurteiler2', 'name as b2name', 'vorname as b2vorname', 'anrede as b2anrede', 'email as b2email'))
                ->joinleft( array( 'st3' => 'stellen'), 	'st3.id = mm2.stelle', 				array( 'bezeichnung as b2stelle'))
                ->where( "m.id = $mitarbeiterID" )
                        ;
            $rows = $adapter->fetchRow( $select ) ;
            return $rows;
    }

NEU */

    /* NEU 12.07.2024
    // Wird gebraucht in Controller:Beurteilung Action:Create, Change, Show und Print
    // Zum Mitarbeiter der Beurteilt wird werden Daten wie Stellenbezeichnung und Führungskompetenz geholt.
    // Angelegt in Version 1.0
    public function getMitarbeiterFuerBeurteilung( $mitarbeiterID )
    {

          try {
              $adapter = $this->getAdapter();
              $select = $adapter->select()
                          ->from( array( 'm' => 'mitarbeiter' ), 										array( '*' ) )
                          ->join( array( 'st1' => 'stellen'), 	'st1.id = m.stelle', 				array( 'bezeichnung as stellebezeichnung', 'fuehrungskompetenz' ))
                          ->where( "m.id = $mitarbeiterID" )
                          ;
              $rows = $adapter->fetchRow( $select ) ;
              return $rows;
          } catch (Exception $e) {
              echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
              die;
          }
    }
NEU */

    /*
     * NEU 12.07.2024
     * // Wird gebraucht in Controller:Mitarbeiter Action:Index um die Liste zu füllen
     * // Angelegt in Version 1.0
     * public function getIndexList($order, $showdeleted)
     * {
     *       try {
     *           $adapter = $this->getAdapter();
     *           $select = $adapter->select()
     *                       ->from( array( 'm' => 'mitarbeiter' ), 										array( '*' ) )
     *                       ->joinLeft( array( 'st' => 'stellen' ), 'm.stelle = st.id', 				array( 'st.bezeichnung as stellen_bezeichnung' ))
     *                       ->order(  explode( ',', $order ) ) ;
     *
     *
     *           if ($showdeleted==0){
     *               $select = $select->where( 'ausgeschieden = false' );
     *           }
     *           $rows = $adapter->fetchAll( $select ) ;
     *           return $rows;
     *       } catch (Exception $e) {
     *           echo 'Exception abgefangen getIndexList: ',  $e->getMessage(), "\n";
     *           die;
     *       }
     * }
     */

    /* NEU 12.07.2024
    // Wird gebraucht den Mitarbeiter über das temporäre Kennwort zu holen
    // Angelegt in Version 1.0
    public function getByKennwort( $kw )
    {
      return $this->fetchRow( sprintf('kennwort = "%s"', $kw )) ;
    }

NEU */

    /* NEU 12.07.2024

    // Wird gebraucht das Kennwort zurück zu setzen
    // Angelegt in Version 1.0
    public function setByKennwort( $kw, $nw )
    {
      $data = array(
                  'kennwort' => new Zend_Db_Expr ('md5("'.$nw.'")' ) );
      $where = $this->getAdapter()->quoteInto('kennwort = ?', $kw ) ;
      $this->update( $data, $where );
    }

NEU */

    /* NEU 12.07.2024
    // Wird gebraucht um in Controller Mitarbeiter Action Show den Mitarbeiter mit allen abhängigen Daten zu holen.
    // Angelegt in Version 1.0
    public function getEntryRelation ( array $params ) {
          $adapter = $this->getAdapter();
          $select = $adapter->select()
                                  ->from(     array( 'm' => 'mitarbeiter' ),    array('*' )                                                                   )
                                  ->joinleft( array( 'st1' => 'stellen'),   'st1.id = m.stelle', array( 'bezeichnung as stellen_bezeichnung')             )
                                  ->joinleft( array( 'r' => 'rechtegruppen'), 'r.id = m.berechtigung', array( 'bezeichnung as gruppe_bezeichnung' ) )
                                    ->where( 'm.id = '.$params[0] ) ;
                          ;
              $rows = $adapter->fetchRow( $select ) ;
              return $rows;
    }
NEU */

    /* NEU 12.07.2024
    // Wird gebraucht um eine Liste aller Mitarbeiter zu holen, die unter dem übergebenen Mitarbeiter als Beurteiler fungieren
    // Angelegt in Version 1.0
    public function getListeBeurteiler( $unterID )
    {
          try {
              $adapter = $this->getAdapter();
              $select = $adapter->select()
                                      ->from(     array( 'm' => 'mitarbeiter' ),    							null                                                                   					)
                                      ->joinleft( array( 's1' => 'stellen' ),	's1.uebergeordnet = m.stelle and s1.fuehrungskompetenz = true',	array( 'ebene as ebene1' )																)
                                      ->joinleft( array( 'm1' => 'mitarbeiter' ),	'm1.stelle = s1.id',	array( 'id as id1', 'name as name1', 'vorname as vorname1')								)

                                      ->joinleft( array( 's2' => 'stellen'),	's2.uebergeordnet = s1.id and s2.fuehrungskompetenz = true', 	array( 'ebene as ebene2' )																)
                                      ->joinleft( array( 'm2' => 'mitarbeiter' ),	'm2.stelle = s2.id',	array( 'id as id2', 'name as name2', 'vorname as vorname2')								)

                                      ->joinleft( array( 's3' => 'stellen'),	's3.uebergeordnet = s2.id and s3.fuehrungskompetenz = true', 	array( 'ebene as ebene3' )																)
                                      ->joinleft( array( 'm3' => 'mitarbeiter' ),	'm3.stelle = s3.id',	array( 'id as id3', 'name as name3', 'vorname as vorname3')								)

                                      ->joinleft( array( 's4' => 'stellen'),	's4.uebergeordnet = s3.id and s4.fuehrungskompetenz = true', 	array( 'ebene as ebene4' )																)
                                      ->joinleft( array( 'm4' => 'mitarbeiter' ),	'm4.stelle = s4.id',	array( 'id as id4', 'name as name4', 'vorname as vorname4')								)

                                      ->joinleft( array( 's5' => 'stellen'),	's5.uebergeordnet = s4.id and s5.fuehrungskompetenz = true', 	array( 'ebene as ebene5' )																)
                                      ->joinleft( array( 'm5' => 'mitarbeiter' ),	'm5.stelle = s5.id',	array( 'id as id5', 'name as name5', 'vorname as vorname5')								)

                                      ->where ("m.id = $unterID" )
                                      ->order( array( 'm1.name', 'm2.name', 'm3.name', 'm4.name', 'm5.name' )) ;
                              ;

              $rows = $adapter->fetchAll( $select ) ;
          }
          catch (Exception $e) {
              echo 'Exception abgefangen getIndexList: ',  $e->getMessage(), "\n";
              die;
          }
              $beurteiler = array();

              foreach ($rows as $item ){
                  if ($item['name1'] != '')
                  {
                      if (!array_key_exists($item['id1'], $beurteiler))
                          $beurteiler[$item['id1']]= array ( 'id'=>$item['id1'], 'name'=>$item['name1'], 'vorname'=>$item['vorname1'], 'ebene'=>$item['ebene1'] ) ;
                  }

                  if ($item['name2'] != '')
                  {
                      if (!array_key_exists($item['id2'], $beurteiler))
                          $beurteiler[$item['id2']]= array ( 'id'=>$item['id2'], 'name'=>$item['name2'], 'vorname'=>$item['vorname2'], 'ebene'=>$item['ebene2'] ) ;
                  }


                  if ($item['name3'] != '')
                  {
                      if (!array_key_exists($item['id3'], $beurteiler))
                          $beurteiler[$item['id3']]= array ( 'id'=>$item['id3'], 'name'=>$item['name3'], 'vorname'=>$item['vorname3'], 'ebene'=>$item['ebene3'] ) ;
                  }

                  if ($item['name4'] != '')
                  {
                      if (!array_key_exists($item['id4'], $beurteiler))
                          $beurteiler[$item['id4']]= array ( 'id'=>$item['id4'], 'name'=>$item['name4'], 'vorname'=>$item['vorname4'], 'ebene'=>$item['ebene4'] ) ;
                  }
                  if ($item['name5'] != '')
                  {
                      if (!array_key_exists($item['id5'], $beurteiler))
                          $beurteiler[$item['id5']]= array ( 'id'=>$item['id5'], 'name'=>$item['name5'], 'vorname'=>$item['vorname5'], 'ebene'=>$item['ebene5'] ) ;
                  }

              }


              return $beurteiler ;
    }
NEU */

    /* NEU 12.07.2024
    // Wird gebraucht um eine Liste aller möglichen Beurteilten unter dem übergebenen Mitarbeiter damit übergeordnete Stellen diese einsehen dürfen.
    // Angelegt in Version 1.0
    public function getListeBeurteilteAsArray( $unterID )
    {
          try {
              $adapter = $this->getAdapter();
              $select = $adapter->select()
                                      ->from(     array( 'm' => 'mitarbeiter' ),    							null                                                                   					)
                                      ->joinleft( array( 's1' => 'stellen' ),	's1.uebergeordnet = m.stelle',	array( 'ebene as ebene1' )																)
                                      ->joinleft( array( 'm1' => 'mitarbeiter' ),	'm1.stelle = s1.id',	array( 'id as id1', 'name as name1', 'vorname as vorname1')								)

                                      ->joinleft( array( 's2' => 'stellen'),	's2.uebergeordnet = s1.id', 	array( 'ebene as ebene2' )																)
                                      ->joinleft( array( 'm2' => 'mitarbeiter' ),	'm2.stelle = s2.id',	array( 'id as id2', 'name as name2', 'vorname as vorname2')								)

                                      ->joinleft( array( 's3' => 'stellen'),	's3.uebergeordnet = s2.id', 	array( 'ebene as ebene3' )																)
                                      ->joinleft( array( 'm3' => 'mitarbeiter' ),	'm3.stelle = s3.id',	array( 'id as id3', 'name as name3', 'vorname as vorname3')								)

                                      ->joinleft( array( 's4' => 'stellen'),	's4.uebergeordnet = s3.id', 	array( 'ebene as ebene4' )																)
                                      ->joinleft( array( 'm4' => 'mitarbeiter' ),	'm4.stelle = s4.id',	array( 'id as id4', 'name as name4', 'vorname as vorname4')								)

                                      ->joinleft( array( 's5' => 'stellen'),	's5.uebergeordnet = s4.id', 	array( 'ebene as ebene5' )																)
                                      ->joinleft( array( 'm5' => 'mitarbeiter' ),	'm5.stelle = s5.id',	array( 'id as id5', 'name as name5', 'vorname as vorname5')								)

                                      ->where ("m.id = $unterID" )
                                      ->order( array( 'm1.name', 'm2.name', 'm3.name', 'm4.name', 'm5.name' )) ;
                              ;
                   //echo $select->__toString() ;
                   //die;
              $rows = $adapter->fetchAll( $select ) ;
          }
          catch (Exception $e) {
              echo 'Exception abgefangen getIndexList: ',  $e->getMessage(), "\n";
              die;
          }
              $untergebene = array();


              foreach ($rows as $item ){
                  if ($item['name1'] != '')
                  {
                      if (!array_key_exists($item['id1'], $untergebene))
                          $untergebene[$item['id1']] = $item['id1'] ;
                  }

                  if ($item['name2'] != '')
                  {
                      if (!array_key_exists($item['id2'], $untergebene))
                          $untergebene[$item['id2']] = $item['id2'] ;
                  }


                  if ($item['name3'] != '')
                  {
                      if (!array_key_exists($item['id3'], $untergebene))
                          $untergebene[$item['id3']] = $item['id3'] ;
                  }

                  if ($item['name4'] != '')
                  {
                      if (!array_key_exists($item['id4'], $untergebene))
                          $untergebene[$item['id4']] = $item['id4'] ;
                  }
                  if ($item['name5'] != '')
                  {
                      if (!array_key_exists($item['id5'], $untergebene))
                          $untergebene[$item['id5']]  = $item['id5'] ;
                  }

              }
          return $untergebene ;
    }

NEU */

    /* NEU 12.07.2024
    // Wird gebraucht um eine Liste aller möglichen Beurteilten unter dem übergebenen Mitarbeiter damit übergeordnete Stellen diese einsehen dürfen.
    // Ausgenommen ist hier die erste Ebene unter dem Angemeldeten Benutzer, das diese vom Übergeordneten Mitarbeiter beurteilt werdern,
    // der dann der Vorgesetzte ist.
    // Angelegt in Version 1.0
    public function getListeBeurteilteAsArrayohneEbeneEins( $unterID )
    {
          try {
              $adapter = $this->getAdapter();
              $select = $adapter->select()
                                      ->from(     array( 'm' => 'mitarbeiter' ),    							null                                                                   					)
                                      ->joinleft( array( 's1' => 'stellen' ),	's1.uebergeordnet = m.stelle',	array( 'ebene as ebene1' )																)
                                      ->joinleft( array( 'm1' => 'mitarbeiter' ),	'm1.stelle = s1.id',	array( 'id as id1', 'name as name1', 'vorname as vorname1')								)

                                      ->joinleft( array( 's2' => 'stellen'),	's2.uebergeordnet = s1.id', 	array( 'ebene as ebene2' )																)
                                      ->joinleft( array( 'm2' => 'mitarbeiter' ),	'm2.stelle = s2.id',	array( 'id as id2', 'name as name2', 'vorname as vorname2')								)

                                      ->joinleft( array( 's3' => 'stellen'),	's3.uebergeordnet = s2.id', 	array( 'ebene as ebene3' )																)
                                      ->joinleft( array( 'm3' => 'mitarbeiter' ),	'm3.stelle = s3.id',	array( 'id as id3', 'name as name3', 'vorname as vorname3')								)

                                      ->joinleft( array( 's4' => 'stellen'),	's4.uebergeordnet = s3.id', 	array( 'ebene as ebene4' )																)
                                      ->joinleft( array( 'm4' => 'mitarbeiter' ),	'm4.stelle = s4.id',	array( 'id as id4', 'name as name4', 'vorname as vorname4')								)

                                      ->joinleft( array( 's5' => 'stellen'),	's5.uebergeordnet = s4.id', 	array( 'ebene as ebene5' )																)
                                      ->joinleft( array( 'm5' => 'mitarbeiter' ),	'm5.stelle = s5.id',	array( 'id as id5', 'name as name5', 'vorname as vorname5')								)

                                      ->where ("m.id = $unterID" )
                                      ->order( array( 'm1.name', 'm2.name', 'm3.name', 'm4.name', 'm5.name' )) ;
                              ;
                   //echo $select->__toString() ;
                   //die;
              $rows = $adapter->fetchAll( $select ) ;
          }
          catch (Exception $e) {
              echo 'Exception abgefangen getIndexList: ',  $e->getMessage(), "\n";
              die;
          }
              $untergebene = array();


              foreach ($rows as $item ){

                  if ($item['name2'] != '')
                  {
                      if (!array_key_exists($item['id2'], $untergebene))
                          $untergebene[$item['id2']] = $item['id2'] ;
                  }


                  if ($item['name3'] != '')
                  {
                      if (!array_key_exists($item['id3'], $untergebene))
                          $untergebene[$item['id3']] = $item['id3'] ;
                  }

                  if ($item['name4'] != '')
                  {
                      if (!array_key_exists($item['id4'], $untergebene))
                          $untergebene[$item['id4']] = $item['id4'] ;
                  }
                  if ($item['name5'] != '')
                  {
                      if (!array_key_exists($item['id5'], $untergebene))
                          $untergebene[$item['id5']]  = $item['id5'] ;
                  }

              }
          return $untergebene ;
    }
NEU */

    /* NEU 12.07.2024

    // Wird gebraucht um eine Liste aller möglichen Beurteilten unter dem übergebenen Mitarbeiter
    // Angelegt in Version 1.0
    public function getListeBeurteilte( $unterID )
    {
          try {
              $adapter = $this->getAdapter();
              $select = $adapter->select()
                                      ->from(     array( 'm' => 'mitarbeiter' ),    							null                                                                   					)
                                      ->joinleft( array( 's1' => 'stellen' ),	's1.uebergeordnet = m.stelle',	array( 'ebene as ebene1' )																)
                                      ->joinleft( array( 'm1' => 'mitarbeiter' ),	'm1.stelle = s1.id',	array( 'id as id1', 'name as name1', 'vorname as vorname1')								)

                                      ->joinleft( array( 's2' => 'stellen'),	's2.uebergeordnet = s1.id', 	array( 'ebene as ebene2' )																)
                                      ->joinleft( array( 'm2' => 'mitarbeiter' ),	'm2.stelle = s2.id',	array( 'id as id2', 'name as name2', 'vorname as vorname2')								)

                                      ->joinleft( array( 's3' => 'stellen'),	's3.uebergeordnet = s2.id', 	array( 'ebene as ebene3' )																)
                                      ->joinleft( array( 'm3' => 'mitarbeiter' ),	'm3.stelle = s3.id',	array( 'id as id3', 'name as name3', 'vorname as vorname3')								)

                                      ->joinleft( array( 's4' => 'stellen'),	's4.uebergeordnet = s3.id', 	array( 'ebene as ebene4' )																)
                                      ->joinleft( array( 'm4' => 'mitarbeiter' ),	'm4.stelle = s4.id',	array( 'id as id4', 'name as name4', 'vorname as vorname4')								)

                                      ->joinleft( array( 's5' => 'stellen'),	's5.uebergeordnet = s4.id', 	array( 'ebene as ebene5' )																)
                                      ->joinleft( array( 'm5' => 'mitarbeiter' ),	'm5.stelle = s5.id',	array( 'id as id5', 'name as name5', 'vorname as vorname5')								)

                                      ->where ("m.id = $unterID" )
                                      ->order( array( 'm1.name', 'm2.name', 'm3.name', 'm4.name', 'm5.name' )) ;
                              ;
                  // echo $select->__toString() ;
              $rows = $adapter->fetchAll( $select ) ;
          }
          catch (Exception $e) {
              echo 'Exception abgefangen getIndexList: ',  $e->getMessage(), "\n";
              die;
          }
              $beurteiler = array();

              foreach ($rows as $item ){
                  if ($item['name1'] != '')
                  {
                      if (!array_key_exists($item['id1'], $beurteiler))
                          $beurteiler[$item['id1']]= array ( 'id'=>$item['id1'], 'name'=>$item['name1'], 'vorname'=>$item['vorname1'], 'ebene'=>$item['ebene1'] ) ;
                  }

                  if ($item['name2'] != '')
                  {
                      if (!array_key_exists($item['id2'], $beurteiler))
                          $beurteiler[$item['id2']]= array ( 'id'=>$item['id2'], 'name'=>$item['name2'], 'vorname'=>$item['vorname2'], 'ebene'=>$item['ebene2'] ) ;
                  }


                  if ($item['name3'] != '')
                  {
                      if (!array_key_exists($item['id3'], $beurteiler))
                          $beurteiler[$item['id3']]= array ( 'id'=>$item['id3'], 'name'=>$item['name3'], 'vorname'=>$item['vorname3'], 'ebene'=>$item['ebene3'] ) ;
                  }

                  if ($item['name4'] != '')
                  {
                      if (!array_key_exists($item['id4'], $beurteiler))
                          $beurteiler[$item['id4']]= array ( 'id'=>$item['id4'], 'name'=>$item['name4'], 'vorname'=>$item['vorname4'], 'ebene'=>$item['ebene4'] ) ;
                  }
                  if ($item['name5'] != '')
                  {
                      if (!array_key_exists($item['id5'], $beurteiler))
                          $beurteiler[$item['id5']]= array ( 'id'=>$item['id5'], 'name'=>$item['name5'], 'vorname'=>$item['vorname5'], 'ebene'=>$item['ebene5'] ) ;
                  }

              }


              return $beurteiler ;
    }

NEU */

    /* NEU 12.07.2024

    // Wird gebraucht um eine Liste aller möglichen Besoldungen der Mitarbeiter zu laden.
    // Angelegt in Version 1.0
    public function getListeBesoldung( $unterID )
    {
          try {
              $adapter = $this->getAdapter();
              $select = $adapter->select()
                                      ->from(     array( 'm' => 'mitarbeiter' ),    							null                                        				)
                                      ->joinleft( array( 's1' => 'stellen' ),	's1.uebergeordnet = m.stelle',	null														)
                                      ->joinleft( array( 'm1' => 'mitarbeiter' ),	'm1.stelle = s1.id',		array( 'besoldung as besoldung1')							)

                                      ->joinleft( array( 's2' => 'stellen'),	's2.uebergeordnet = s1.id', 	null														)
                                      ->joinleft( array( 'm2' => 'mitarbeiter' ),	'm2.stelle = s2.id',		array( 'besoldung as besoldung2')							)

                                      ->joinleft( array( 's3' => 'stellen'),	's3.uebergeordnet = s2.id', 	null														)
                                      ->joinleft( array( 'm3' => 'mitarbeiter' ),	'm3.stelle = s3.id',		array( 'besoldung as besoldung3')							)

                                      ->joinleft( array( 's4' => 'stellen'),	's4.uebergeordnet = s3.id', 	null														)
                                      ->joinleft( array( 'm4' => 'mitarbeiter' ),	'm4.stelle = s4.id',		array( 'besoldung as besoldung4')							)

                                      ->joinleft( array( 's5' => 'stellen'),	's5.uebergeordnet = s4.id', 	null														)
                                      ->joinleft( array( 'm5' => 'mitarbeiter' ),	'm5.stelle = s5.id',		array( 'besoldung as besoldung5')							)

                                      ->where ("m.id = $unterID" )
                                      ->order( array( 'm1.besoldung', 'm2.besoldung', 'm3.besoldung', 'm4.besoldung', 'm5.besoldung' )) ;
                              ;
                  // echo $select->__toString() ;
              $rows = $adapter->fetchAll( $select ) ;
          }
          catch (Exception $e) {
              echo 'Exception abgefangen getIndexList: ',  $e->getMessage(), "\n";
              die;
          }
              $besoldung = array();


              foreach ($rows as $item ){

                  if ($item['besoldung1'] != '')
                  {
                      if (!array_key_exists($item['besoldung1'], $besoldung))
                          $besoldung[$item['besoldung1']]= $item['besoldung1'] ;
                  }

                  if ($item['besoldung2'] != '')
                  {
                      if (!array_key_exists($item['besoldung2'], $besoldung))
                          $besoldung[$item['besoldung2']]= $item['besoldung2'] ;
                  }

                  if ($item['besoldung3'] != '')
                  {
                      if (!array_key_exists($item['besoldung3'], $besoldung))
                          $besoldung[$item['besoldung3']]= $item['besoldung3'] ;
                  }

                  if ($item['besoldung4'] != '')
                  {
                      if (!array_key_exists($item['besoldung4'], $besoldung))
                          $besoldung[$item['besoldung4']]= $item['besoldung4'] ;
                  }
                  if ($item['besoldung5'] != '')
                  {
                      if (!array_key_exists($item['besoldung5'], $besoldung))
                          $besoldung[$item['besoldung5']]= $item['besoldung5'] ;
                  }

              }
              ksort($besoldung);

              return $besoldung ;
    }
NEU */

    /* NEU 12.07.2024
    // Wird gebraucht um eine Liste aller möglichen anstellungen der Mitarbeiter zu laden.
    // Angelegt in Version 1.0
    public function getListeanstellung( $unterID )
    {
        $anstellung = array();
          try {
              $adapter = $this->getAdapter();
              $select = $adapter->select()
                                      ->from(     array( 'm' => 'mitarbeiter' ),    							null                                        				)
                                      ->joinleft( array( 's1' => 'stellen' ),	's1.uebergeordnet = m.stelle',	null														)
                                      ->joinleft( array( 'm1' => 'mitarbeiter' ),	'm1.stelle = s1.id',		array( 'anstellung as anstellung1')							)

                                      ->joinleft( array( 's2' => 'stellen'),	's2.uebergeordnet = s1.id', 	null														)
                                      ->joinleft( array( 'm2' => 'mitarbeiter' ),	'm2.stelle = s2.id',		array( 'anstellung as anstellung2')							)

                                      ->joinleft( array( 's3' => 'stellen'),	's3.uebergeordnet = s2.id', 	null														)
                                      ->joinleft( array( 'm3' => 'mitarbeiter' ),	'm3.stelle = s3.id',		array( 'anstellung as anstellung3')							)

                                      ->joinleft( array( 's4' => 'stellen'),	's4.uebergeordnet = s3.id', 	null														)
                                      ->joinleft( array( 'm4' => 'mitarbeiter' ),	'm4.stelle = s4.id',		array( 'anstellung as anstellung4')							)

                                      ->joinleft( array( 's5' => 'stellen'),	's5.uebergeordnet = s4.id', 	null														)
                                      ->joinleft( array( 'm5' => 'mitarbeiter' ),	'm5.stelle = s5.id',		array( 'anstellung as anstellung5')							)

                                      ->where ("m.id = $unterID" )
                                      ->order( array( 'm1.anstellung', 'm2.anstellung', 'm3.anstellung', 'm4.anstellung', 'm5.anstellung' )) ;
                              ;
              // echo $select->__toString() ;
              $rows = $adapter->fetchAll( $select ) ;
          }
          catch (Exception $e) {
              echo 'Exception abgefangen getIndexList: ',  $e->getMessage(), "\n";
              die;
          }



              foreach ($rows as $item ){

                  if ($item['anstellung1'] != '')
                  {
                      if (!array_key_exists($item['anstellung1'], $anstellung))
                          $anstellung[$item['anstellung1']]= $item['anstellung1'] ;
                  }

                  if ($item['anstellung2'] != '')
                  {
                      if (!array_key_exists($item['anstellung2'], $anstellung))
                          $anstellung[$item['anstellung2']]= $item['anstellung2'] ;
                  }

                  if ($item['anstellung3'] != '')
                  {
                      if (!array_key_exists($item['anstellung3'], $anstellung))
                          $anstellung[$item['anstellung3']]= $item['anstellung3'] ;
                  }

                  if ($item['anstellung4'] != '')
                  {
                      if (!array_key_exists($item['anstellung4'], $anstellung))
                          $anstellung[$item['anstellung4']]= $item['anstellung4'] ;
                  }
                  if ($item['anstellung5'] != '')
                  {
                      if (!array_key_exists($item['anstellung5'], $anstellung))
                          $anstellung[$item['anstellung5']]= $item['anstellung5'] ;
                  }

              }
              if ( array_key_exists( 0, $anstellung) )
                  unset( $anstellung[0]) ;
              ksort($anstellung);
              return $anstellung ;
    }

NEU */

    /* NEU 12.07.2024
    // Wird gebraucht in den Auswertungen um die Ebene des angemeldeten Benutzers zu ermitteln
    // Angelegt in Version 1.0
    public function getMitarbeiterEbene ( $mitarbeiterID )
    {
          $adapter = $this->getAdapter();
          $select = $adapter->select()
                                  ->from(     array( 'm' => 'mitarbeiter' ), null                                                                   )
                                  ->joinleft( array( 'st1' => 'stellen'),   'st1.id = m.stelle', array( 'ebene')       )
                                    ->where( 'm.id = '.$mitarbeiterID ) ;
                          ;
              $rows = $adapter->fetchRow( $select ) ;
              return $rows['ebene'];
    }
NEU */

    /* NEU 12.07.2024
    public function getMitarbeiterStelle ( $StelleID )
    {
          $adapter = $this->getAdapter();
          $select = $adapter->select()
                                  ->from(     array( 'm' => 'mitarbeiter' ), null                                                                   )
                                  ->joinleft( array( 'st1' => 'stellen'),   'st1.id = m.stelle', array( 'ebene')       )
                                  ->where( 'm.stelle = '.$StelleID ) ;
                          ;
              $rows = $adapter->fetchRow( $select ) ;
              return $rows['ebene'];
    }
NEU */

    /* NEU 12.07.2024
    public function getEntrybyPersonalNr( array $params )
    {
      return $this->fetchRow('personalnr = '.$params[0] );
    }

NEU */
}

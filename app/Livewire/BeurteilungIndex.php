<?php

namespace App\Livewire;

use App\Models\Beurteilung;

use App\Models\Mitarbeiter;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Session; // Session-Fassade hinzufügen
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;


class BeurteilungIndex extends Component
{

	public $user;
	public $id;

    public $userID;

    public $datumVon;
    public $datumBis;
    public $faelligeMitarbeiterUnterbeurteiler1;
    public $mitarbeiterUnterbeurteiler1;
    public $mitarbeiterUnterbeurteiler2;
    public $mitarbeiterAllerEbenenUnterBeurteiler1;

    public $aktiveBeurteilungenBeurteiler1;
    public $aktiveBeurteilungenBeurteiler2;

    public $kopfueberschrift = "Beurteilungen";

    public function mount()
    {
        //$this->beurteilungen = Beurteilung::all();
    }



    public function render()
    {
        Log::Info("BeurteilungIndex.php render()-Anfang");
		// Grundeinstellungen


		// Grunddaten
		//  $id 			= Auth::user()->id; // $this->view->loggedInID() ;
		$this->datumVon		= date("d.m.Y", mktime(0, 0, 0, date("m")-2, 1,  date("Y")) );
		$this->datumBis		= date('d.m.Y');


		if (Auth::user()){

			log::info("app/Livewire/BeurteilungIndex.php => render() => Auth::user() wurde gefunden!");

			$this->user = Auth::user();

			$userID = $this->user->id;

		}
		else
		{
			$this->id = 79;
			$this->user = User::find($this->id);
			if (!$this->user){
				log::error("app/Livewire/BeurteilungIndex.php => render() => Auth wurde nicht gefunden!");
				abort(403);
			}
		}

		$mmitarbeiter = new Mitarbeiter();

        $arr = $mmitarbeiter->getMitarbeiterArrayUnterBeurteiler1( $this->user->id);

		$this->faelligeMitarbeiterUnterbeurteiler1 = $mmitarbeiter->getFaelligeMitarbeiterBeurteiler1( $this->user->id);

        $this->mitarbeiterUnterbeurteiler1 = $mmitarbeiter->getMitarbeiterUnterBeurteiler1( $this->user->id);

        $this->mitarbeiterAllerEbenenUnterBeurteiler1  = $mmitarbeiter->getMitarbeiterAllerEbenenUnterBeurteiler1( $this->user->id);

        $this->mitarbeiterUnterbeurteiler2 = $mmitarbeiter->getMitarbeiterUnterBeurteiler2( $this->user->id);


        $mitarbeiterBeurteiler2Array = $mmitarbeiter->getMitarbeiterArrayUnterBeurteiler2($this->user->id);
        $mitarbeiterBeurteiler1Array = $mmitarbeiter->getMitarbeiterArrayUnterBeurteiler1($this->user->id);

        $bbeurteilung = new Beurteilung();
        $this->aktiveBeurteilungenBeurteiler1 = $bbeurteilung->getAktiveVonBeurteiler1( $mitarbeiterBeurteiler1Array );


        $this->aktiveBeurteilungenBeurteiler2 = $bbeurteilung->getAktiveVonBeurteiler2( $mitarbeiterBeurteiler2Array );


        Log::Info("BeurteilungIndex.php render()-Ende");
        return view('livewire.beurteilung.index');
    }

}

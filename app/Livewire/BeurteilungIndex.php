<?php

namespace App\Livewire;

use App\Models\Beurteilung;
use App\Models\Bdetails;
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
    public $beurteilungen;
    public $datumvon;
    public $datumbis;

    public $kopfueberschrift = "Beurteilungen";

    public function mount()
    {
        $this->beurteilungen = Beurteilung::all();
    }



    public function render()
    {
		// Grundeinstellungen 
		

		// Grunddaten 		
		//  $id 			= Auth::user()->id; // $this->view->loggedInID() ;
		$this->datumvon		= date("d.m.Y", mktime(0, 0, 0, date("m")-2, 1,  date("Y")) );
		$this->datumbis		= date('d.m.Y');			
		
		
		if (Auth::user()){
			
			log::info("app/Livewire/BeurteilungIndex.php => render() => Auth::user() wurde nicht gefunden!");
			
			$this->user = Auth::user();

			$userID = $this->user->id;
			
		}
		else
		{
			$this->id = 1;
			$this->user = User::find($this->id);
			if (!$this->user){
				log::error("app/Livewire/BeurteilungIndex.php => render() => Auth wurde nicht gefunden!");
				abort(403);
			}

			
		}

		$mmitarbeiter = new Mitarbeiter();
		$ids = $mmitarbeiter->getFaelligeMitarbeiterBeurteiler1( $this->user->id);

		#$ids = $mmitarbeiter->getMitarbeiterohneBeurteilungAlsBeurteiler2asArray( $this->user->id, $this->datumvon, $this->datumbis);

		$mBeurteilung = new Beurteilung();
		$this->beurteilungen = array();
		//echo "<pre>";
		for ($i=0; $i < count($ids);$i++){
			// echo sprintf ("%s<br>", $beurteilungen[$i]);
			// var_dump( $mBeurteilung->getBeurteilungenFromBeurteiler2( $beurteilungen[0] ) );
			$bb = $mBeurteilung->getBeurteilungenFromBeurteiler2( $ids[$i] );	 
			if ($bb->isOK)
			{
				$this->beurteilungen[] = $bb;
			}
			
		}

        return view('livewire.beurteilung.index');
    }

}

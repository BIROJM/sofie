<?php
class ApiController extends BaseController {
	
	public function __construct()
	{
		//parent::__construct();
		$this->beforeFilter('auth.mobile');
	}
	
	public function auth()
	{
		
		$username = Input::get('username');
		$password = Input::get('password');
		$response = UserAuth::check($username, $password);
		//if(UserAuth::$isValid)
		return Response::json($response);
	}
	
	//Appel d'une creation d'ouvrage via le mobile
	
	public function createForage()
	{
	   //var_dump(Input::all()); exit;
	   //if (Request::isJson())
       // {
		  
		 //  $userInfos = UserAuth::check(Input::get('username'), Input::get('password'));
		   
		   // echo Session::get('UserIsValid'); exit;
		   
		 //  if(UserAuth::$isValid)
		//	{
						
		   //var_dump(Input::all()); exit;
		    $response = array();
			Utils::$user = Input::get('username');
			Utils::$imei = Input::get('imei');
	    	Utils::log("---------------------- Debut de reception de données de création d'ouvrage depuis un mobile ----------------------"); 
			Utils::log("Données reçues : " . urldecode(http_build_query(Input::all())));
			$regles = array(
								'NumIRH' => 'required|unique:t_ouvrage',
								'localites' => 'required',
								'region' => 'required|numeric',
							);
				
				$validation = Validator::make(Input::all(), $regles);
				if ($validation->fails()) {
					  $response = array('status' =>1, 'message' => 'Erreur dans la saisie des données', 'errors' => $validation->errors()->toArray());
				}
				else
				{
					try
					{
							
						$ouvrage= new Ouvrage;
						$ouvrage->origin = "M";
						$idOuvrage = Utils::getEntityKey('Ouvrage');
						$ouvrage->IDOuvrage = $idOuvrage;
						$ouvrage->CodeOuvrage = Input::get('region') . '' . $idOuvrage; //Input::get('NumIRH'); //Input::get('CodeOuvrage');
						$ouvrage->TypeOuvrage = Input::get('TypeOuvrage');
						$ouvrage->StatutOuvrage = 1;
						$ouvrage->Longitude = Input::get('Longitude');
						$ouvrage->Latitude = Input::get('Latitude');
						$ouvrage->Altitude = Input::get('Altitude');
						$ouvrage->IDLocalite = Input::get('localites');
						$ouvrage->Canton = Input::get('Canton');
						$ouvrage->IDRegion = Input::get('region');
						$ouvrage->Quartier = Input::get('Quartier');
						$ouvrage->Prefecture = Input::get('Prefecture');
						$ouvrage->NumIRH = Input::get('NumIRH');
						$ouvrage->AutreNumID = Input::get('AutreNumID');
						$ouvrage->Designation = Input::get('Designation');
						$ouvrage->OperateurSaisie = Input::get('NomOperateur'); // Input::get('username'); 
						$ouvrage->DateSaisie = Input::get('DateSaisie');
						$ouvrage->NumLocaliteProgres = Input::get('NumLocaliteProgres');
						$ouvrage->EtatInitialCaptage = Input::get('EtatInitialCaptage');
						$ouvrage->Propriete = Input::get('Propriete');
						$ouvrage->NomDuProjet = Input::get('NomProjet');
						$ouvrage->Financement = Input::get('Financement');
						$ouvrage->IngenieurConseil = Input::get('IngenieurConseil');
						$ouvrage->Entreprise = Input::get('Entreprise');
						$ouvrage->DateFinForation = Input::get('DateFinForation');
						$ouvrage->profondeurTotale = Input::get('profondeurTotale');
						$ouvrage->ProfondeurEquipee = Input::get('ProfondeurEquipee');
						$ouvrage->NiveauStatique = Input::get('NiveauStatique');
						$ouvrage->DateNs = Input::get('DateNs');
						$ouvrage->Geomorphologie = Input::get('Geomorphologie');
						$ouvrage->EpaisseurAlteration = Input::get('EpaisseurAlteration');
						$ouvrage->NomAquifere = Input::get('NomAquifere');
						$ouvrage->LithologieAquifere = Input::get('LithologieAquifere');
						$ouvrage->ProfondeurToit = Input::get('ProfondeurToit');
						$ouvrage->ProfondeurMur = Input::get('ProfondeurMur');
						$ouvrage->DatePrelevement = Input::get('DatePrelevement');
						$ouvrage->Temperature = Input::get('Temperature');
						$ouvrage->Conductivite = Input::get('Conductivite');
						$ouvrage->Ph = Input::get('Ph');
						$ouvrage->FerTotal = Input::get('FerTotal');
						$ouvrage->Usage = Input::get('Usage');
						$ouvrage->Nitrates = Input::get('Nitrates');
						$ouvrage->Couleur = Input::get('Couleur');
						$ouvrage->Turbidite = Input::get('Turbidite');
						$ouvrage->MarquePompe = Input::get('MarquePompe');
						$ouvrage->DateInstallPompe = Input::get('DateInstallPompe');
						$ouvrage->ProfondeurInstallPompe = Input::get('ProfondeurInstallPompe');
						$ouvrage->Debit = Input::get('Debit');
						$ouvrage->save();
						
						if (Input::has('SuiviPhysicoChimique'))
						{				
							$suivis = Input::get('SuiviPhysicoChimique');
							
							foreach($suivis as $suivi)
							{
								$suiviPhysicoChimique = new SuiviPhysicoChimique;
								$id = Utils::getEntityKey('SuiviPhysicoChimique');
								$suiviPhysicoChimique->IDSuiviPhysicoChimique = $id;
								$suiviPhysicoChimique->Date = $suivi['Date'];
								$suiviPhysicoChimique->Ph = $suivi['Ph'];
								$suiviPhysicoChimique->Cond = $suivi['Cond'];
								$suiviPhysicoChimique->ResSec = $suivi['ResSec'];
								
								//$suiviPhysicoChimique->Date = $suivi['Date'];
								//$suiviPhysicoChimique->ResSec = Input::get('ResSec');
								$suiviPhysicoChimique->Ca = $suivi['Ca'];
								$suiviPhysicoChimique->Mg = $suivi['Mg'];
								$suiviPhysicoChimique->Na = $suivi['Na'];
								
								$suiviPhysicoChimique->K = $suivi['K'];
								$suiviPhysicoChimique->Cl = $suivi['Cl'];
								$suiviPhysicoChimique->No2 = $suivi['No2'];
								$suiviPhysicoChimique->No3 = $suivi['No3'];
								
								$suiviPhysicoChimique->So4 = $suivi['So4'];
								$suiviPhysicoChimique->Hco3 = $suivi['Hco3'];
								
								$suiviPhysicoChimique->F = $suivi['F'];
								$suiviPhysicoChimique->FeTot = $suivi['FeTot'];
								
								$suiviPhysicoChimique->As = $suivi['As'];  
												
								$ouvrage->suiviPhysicoChimique()->save($suiviPhysicoChimique);
							}				
						}
						
						if (Input::has('CoupeGeologique'))
						{				
							$coupesGeo = Input::get('CoupeGeologique');
							
							foreach($coupesGeo as $coupeGeo)
							{
								$coupeGeologique = new CoupeGeologique;
								$id = Utils::getEntityKey('CoupeGeologique');
								$coupeGeologique->IDCoupeGeologique = $id;
								$coupeGeologique->CoteSup = $coupeGeo['CoteSup'];
								$coupeGeologique->CoteInf = $coupeGeo['CoteInf'];
								$coupeGeologique->Lithographie = $coupeGeo['Lithographie'];
								$coupeGeologique->Description = $coupeGeo['Description'];
								
								$ouvrage->coupeGeologique()->save($coupeGeologique);
							}
						}
						
						if (Input::has('EssaisPompage'))
						{
							$essaisPomp = Input::get('EssaisPompage');
							
							foreach($essaisPomp as $essaiPomp)
							{
								$essaisPompage = new EssaisPompage;
								$id = Utils::getEntityKey('EssaisPompage');
								$essaisPompage->IDEssaisPompage = $id;
								$essaisPompage->Date = $essaiPomp['Date'];
								$essaisPompage->TypeEssai = $essaiPomp['TypeEssai'];
								$essaisPompage->DureeEssai = $essaiPomp['DureeEssai'];
								$essaisPompage->DebitMax = $essaiPomp['DebitMax'];
								$essaisPompage->Rabattement = $essaiPomp['Rabattement'];
								$essaisPompage->DebitCritique = $essaiPomp['DebitCritique'];						
								$essaisPompage->Transmissivite = $essaiPomp['Transmissivite'];
								$essaisPompage->Emmagasinage = $essaiPomp['Emmagasinage'];
								
								$ouvrage->essaisPompage()->save($essaisPompage);
							}
						}
						
						if (Input::has('EquipementForage'))
						{				
							$equipsForage = Input::get('EquipementForage');
							
							foreach($equipsForage as $equipForage)
							{
								$equipementForage = new EquipementForage;
								$id = Utils::getEntityKey('EquipementForage');
								$equipementForage->IDEquipementForage = $id;
								$equipementForage->Nature = $equipForage['Nature'];
								$equipementForage->ProfSup = $equipForage['ProfSup'];
								$equipementForage->ProfInf = $equipForage['ProfInf'];
								$equipementForage->Diametre = $equipForage['Diametre'];
								
								$ouvrage->equipementForage()->save($equipementForage);
							}
						}
						
						if (Input::has('VenuEauPrincipale'))
						{				
							$venuEauPrincipale = Input::get('VenuEauPrincipale');
							
							foreach($venuEauPrincipale as $venuEau)
							{
								$venuEauObject = new VenuEauPrincipale;
								$id = Utils::getEntityKey('VenuEauPrincipale');
								$venuEauObject->IDVenuEauPrincipale = $id;
								$venuEauObject->Profondeur = $venuEau['Profondeur'];
								$venuEauObject->DebitCumule = $venuEau['DebitCumule'];
												
								$ouvrage->venuEauPrincipale()->save($venuEauObject);
							}
						}
						
						$response = array('status' => 0, 'message' => 'Ouvrage enregistré avec succès');
					}
					catch(\Exception $e)
					{
						$response = array('status' => 1, 'message' => $e->getMessage());
					}
					
				}
			Utils::log("Result : " . urldecode(http_build_query($response)));
		Utils::log("---------------------- Fin de reception de données de création d'ouvrage depuis un mobile ----------------------"); 
		return Response::json($response);
	}
	
	
	public function updateForageData()
	{
	//	if (Request::isJson())
    //    {
			$response = array();
			Utils::$user = Input::get('username');
			Utils::$imei = Input::get('imei');
	    	Utils::log("---------------------- Debut de reception de données de suivi d'ouvrage depuis un mobile ----------------------"); 
			Utils::log("Données reçues : " . urldecode(http_build_query(Input::all())));
			
			$regles = array(
								'NumIRH' => 'required',
								//'NumIRH' => 'exists:t_ouvrage,NumIRH' 
							);
			
			$validation = Validator::make(Input::all(), $regles);
			if ($validation->fails()) {
				$response = array('status' =>1, 'message' => "Erreur lors de l'envoi des données", 'errors' => $validation->errors()->toArray());
			}
			else
			{	
				//$ouvrage = Ouvrage::where('NumIRH', Input::get('NumIRH'))->first();
								
				$ouvrage = $this->getOuvrageID(Input::get('NumIRH'));
				
				if(!$ouvrage)
				{
					$response = array('status' =>1, 'message' => "Erreur lors de l'envoi des données", 'errors' => 'N° IRH inexistant');
					//exit;
				}
				
				else 
				{
					try
					{
						$collecte = new Collecte;
						$id = Utils::getEntityKey('Collecte');
						$collecte->IDCollecte = $id;
						$collecte->IDOuvrage = $ouvrage->IDOuvrage;	
						$collecte->origin = "M";
						$collecte->DateSaisie = Input::get('DateSaisie');
						$collecte->DateRemplissage = Input::get('DateRemplissage');
						$collecte->NomOperateur = Input::get('NomOperateur');
						$collecte->NomAgentSaisie = Input::get('username'); //Input::get('NomAgentSaisie');
						$collecte->Service = Input::get('Service');
						$collecte->EtatOuvrage = Input::get('EtatOuvrage');
						$collecte->CodeCauseSiAbandon = Input::get('CodeCauseSiAbandon');
						$collecte->NumIRH = Input::get('NumIRH');
						$collecte->CauseDestruction = Input::get('CauseDestruction');
						$collecte->PereniteAnneeNbMois = Input::get('PereniteAnneeNbMois');
						$collecte->PereniteAnneeDsJournee = Input::get('PereniteAnneeDsJournee');
						$collecte->SuperstructureRehabilite = Input::get('SuperstructureRehabilite');
						$collecte->EtatMargelle = Input::get('EtatMargelle');
						$collecte->EtancheiteForage = Input::get('EtancheiteForage');
						$collecte->EtatFixationPompe = Input::get('EtatFixationPompe');
						$collecte->EtatAntiBourbier = Input::get('EtatAntiBourbier');
						$collecte->EtatCloture = Input::get('EtatCloture');
						$collecte->EtatRigoleEvacuation = Input::get('EtatRigoleEvacuation');
						$collecte->EtatPuitPerdu = Input::get('EtatPuitPerdu');
						$collecte->PropreteInterieurCloture = Input::get('PropreteInterieurCloture');
						$collecte->PropreteExterieurCloture = Input::get('PropreteExterieurCloture');
						$collecte->SourcePollution1 = Input::get('SourcePollution1');
						$collecte->SourcePollution2 = Input::get('SourcePollution1');
						$collecte->PresenceDeferiseur = Input::get('PresenceDeferiseur');
						$collecte->DeferiseurUtilise = Input::get('DeferiseurUtilise');
						$collecte->EtatDeferiseur = Input::get('EtatDeferiseur');
						$collecte->NbPompe = Input::get('NbPompe');
						$collecte->MarquePompe = Input::get('MarquePompe');
						$collecte->PompeRemplace = Input::get('PompeRemplace');
						$collecte->AnneePosePompe = Input::get('AnneePosePompe');
						$collecte->FinancementRemplacement = Input::get('FinancementRemplacement');
						$collecte->NomProjet = Input::get('NomProjet');
						$collecte->EtatPompe = Input::get('EtatPompe');
						$collecte->DureePanne = Input::get('DureePanne');
						$collecte->CauseNonReparation = Input::get('CauseNonReparation');
						$collecte->Turbidite = Input::get('Turbidite');
						$collecte->OdeurEau = Input::get('OdeurEau');
						$collecte->GoutEau = Input::get('GoutEau');
						$collecte->PelliculeEnSurface = Input::get('PelliculeEnSurface');
						$collecte->PresenceVers = Input::get('PresenceVers');
						$collecte->Conductivite = Input::get('Conductivite');				
						$collecte->Ph = Input::get('Ph');
						$collecte->NitratesNo3 = Input::get('NitratesNo3');
						$collecte->NitritesNo2 = Input::get('NitritesNo2');
						$collecte->FerTotal = Input::get('FerTotal');
						$collecte->modeGestionOuvrage = Input::get('modeGestionOuvrage');
						$collecte->PresenceUniteGestion = Input::get('PresenceUniteGestion');
						$collecte->VillageUE = Input::get('VillageUE');
						$collecte->NumVillageUE = Input::get('NumVillageUE');				
						$collecte->AssistanceBienfaiteur = Input::get('AssistanceBienfaiteur');
						$collecte->ModePaiementEau = Input::get('ModePaiementEau');
						$collecte->PrixSeau20Litres = Input::get('PrixSeau20Litres');
						$collecte->prixBassine35Litres = Input::get('prixBassine35Litres');
						$collecte->NomArtisanReparateur = Input::get('NomArtisanReparateur');
						$collecte->VillageResidenceReparateur = Input::get('VillageResidenceReparateur');
						$collecte->CahierEntretientPompe = Input::get('CahierEntretientPompe');
						$collecte->ContratEntretienArtisan = Input::get('ContratEntretienArtisan');				
						$collecte->TypeContrat = Input::get('TypeContrat');
						$collecte->Commentaires = Input::get('Commentaires');	
					
						$collecte->save();	
						$response = array('status' =>0, 'message' => 'Mise à jour enregistré');
					}
					catch(\Exception $e)
					{
						$response = array('status' => 1, 'message' => $e->getMessage());
					}					
				}
			}
		Utils::log("Result : " . urldecode(http_build_query($response)));
		Utils::log("---------------------- Fin de reception de données de suivi d'ouvrage depuis un mobile ----------------------"); 
	
		return Response::json($response);
		//}
		
	}	
	
	
	public function getForageDataByRegion($idRegion)
	{
		$response = array();
		Utils::$user = Input::get('username');
		Utils::$imei = Input::get('imei');
		
		Utils::log(" Recuperation de données de forage depuis un mobile ");
		
		if(!empty($idRegion))
		{			
			Utils::log("Region : " . Utils::getRegion($idRegion));
			$ouvrages = DB::Table('t_ouvrage')
			->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
			->select('t_ouvrage.CodeOuvrage', 't_ouvrage.NumIRH', 't_ouvrage.Latitude', 't_ouvrage.Longitude', 't_ouvrage.TypeOuvrage', 't_ouvrage.Altitude', 't_ouvrage.TypeOuvrage', 't_ouvrage.Propriete', 
					't_ouvrage.NumLocaliteProgres', 't_ouvrage.Designation', 't_ouvrage.Quartier', 't_ouvrage.Prefecture', 't_ouvrage.Canton', 't_ouvrage.IDRegion', 't_ouvrage.IDLocalite')
			->get();
			Utils::log("Nombre d'ouvrages retournés : " . count($ouvrages));
		}
		if(count($ouvrages) > 0)
		{
			Utils::log("Données : " . urldecode(http_build_query($ouvrages)));
		}
		
		Utils::log(" Fin recuperation de données de forage depuis un mobile ");
		return Response::json($ouvrages);		 
	}
	
	public function getForageDataByNumIRH($numIrh)
	{
		
		if(!empty($numIrh))
		{
			
			$ouvrages = DB::Table('t_ouvrage')
			->whereRAW('t_ouvrage.NumIRH = ' . $numIrh)
			->select('t_ouvrage.CodeOuvrage', 't_ouvrage.NumIRH', 't_ouvrage.Latitude', 't_ouvrage.Longitude', 't_ouvrage.TypeOuvrage', 't_ouvrage.Altitude', 't_ouvrage.TypeOuvrage', 't_ouvrage.Propriete', 
					't_ouvrage.NumLocaliteProgres', 't_ouvrage.Designation', 't_ouvrage.Quartier', 't_ouvrage.Prefecture', 't_ouvrage.Canton', 't_ouvrage.IDRegion', 't_ouvrage.IDLocalite')
			->get();
		}
				
		return Response::json($ouvrages);		 
	}	
		
	public function getLocalite($idRegion)
	{
		$localites = Localite::where('IDRegion', $id)->select('NomLocalite', 'IDLocalite')->orderBy('NomLocalite')->get();
		return Response::json($localites);	
	}
	
	public function getConfig($idRegion)
	{
		$response = array();
		$localites = array();
		
		Utils::$user = Input::get('username');
		Utils::$imei = Input::get('imei');
		
		Utils::log("Recuperation des données de configuration depuis un mobile ");
		try{
				
			$regions = DB::table('t_region')
									->orderBy('IDRegion', 'asc')
									->lists('NomRegion', 'IDRegion');
			
			if($idRegion != 0)
			{
				Utils::log("Region : " . Utils::getRegion($idRegion));
				$query = DB::table('t_localite')->join('t_region','t_region.IDRegion','=','t_localite.IDRegion')
												->whereRAW('t_localite.IDRegion = ' . $idRegion)								
												->orderBy('NomLocalite', 'asc');
				$localites = $query->lists('NomLocalite', 'IDLocalite');
			}
			
			$user = User::where('username', 'adminmobile')->first();
			if($user)
			{
				$response = array("regions" => $regions, "localites" => $localites, 'adminuser' => array('username' => $user->username, 'password' => $user->password));
			}
			else
			{
				$response = array("regions" => $regions, "localites" => $localites);
			}
			
			Utils::log(" Result : " . urldecode(http_build_query($response)));
			Utils::log("Fin de recuperation des données de configuration depuis un mobile ");
			return $response;
		}
		catch(\Exception $e)
		{
			$response =  array('message' => "Une erreur est survenue", 'errors' => array($e->getCode() => array($e->getMessage())), 'status' => '1');
			Utils::log(" Result : " . urldecode(http_build_query($response)));
			Utils::log("Fin de recuperation des données de configuration depuis un mobile ");
			return Response::json($response);
		}
		
	}
	
	public function getOuvrageID($numIrh)
	{
		$ouvrage = Ouvrage::where('NumIRH', $numIrh)->first();
		if($ouvrage)
		{
			return $ouvrage;
		}
		else
		{
			return false;
		}
	}
}
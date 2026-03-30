<?php

class SynchroController extends \BaseController {

	protected $ouvrage;
	
	//Synchronisation des statuts d'ouvrage via SMS
	public function sendForageStatusSynchro()
	{			
		//$regionalSmsGatewayNumber = Config::get('sofie.regionalSmsGatewayNumber');	
		$separator = ';';
		Utils::log("---------------------- Debut de synchronisation des statuts d'ouvrage par SMS ----------------------");
		$j = 1;
		//echo count($regionalSmsGatewayNumber); exit;
		
		$smsGateway = Utils::getRegionalSmsGatewayNumber();
		
		//print_r($smsGateway); exit;
		
		foreach($smsGateway as $gateway)
		{
			$forageStatus  = $this->getForageStatus($gateway->IDRegion);
			
			Utils::log("Nombre de changement de statut d'ouvrage à traiter pour la région  " . $gateway->NomRegion . " : " . count($forageStatus));
			//echo $i;
			if(count($forageStatus) > 0)
			{			
				foreach($forageStatus as $status)
				{
					$msg = $status->IDRegion . $separator . $status->IDOuvrage  . $separator .  $status->CodeOuvrage . $separator . $status->NumPanne . $separator . $status->statutPanne;
					//$this->sendSms($msg, $gateway->numero_modem, $status->NumPanne);
					$this->insertSMSInTable($gateway->numero_modem, $msg, $status->NumPanne);
					sleep(1);
					//echo $msg .'<br>';
				}
			}
			
		}
				
		/*
		
		for($i=0; $i<count($regionalSmsGatewayNumber); $i++)
		{
			$forageStatus  = $this->getForageStatus($j);
			
			Utils::log("Nombre de changement de statut d'ouvrage à traiter dans la région  " . $j . " : " . count($forageStatus));
			//echo $i;
			if(count($forageStatus) > 0)
			{			
				foreach($forageStatus as $status)
				{
					$msg = $status->IDRegion . $separator . $status->IDOuvrage  . $separator .  $status->CodeOuvrage . $separator . $status->NumPanne . $separator . $status->statutPanne;
					$this->sendSms($msg, $regionalSmsGatewayNumber[$j]);					
					//echo $msg .'<br>';
				}
			}
			$j++;
		}
		
		*/
		Utils::log("---------------------- Fin de synchronisation des statuts d'ouvrage par SMS ----------------------");
	}	
	
	public function getForageStatus($idRegion)
	{
		$pannes = DB::Table('t_panne')
		->join('t_ouvrage','t_ouvrage.IDOuvrage','=','t_panne.IDOuvrage')
		->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
		->whereRAW("t_panne.syncStatus = 'N'")->get(array('t_ouvrage.IDRegion','t_ouvrage.CodeOuvrage', 't_ouvrage.IDOuvrage','t_panne.NumPanne', 't_ouvrage.statutPanne'));
		return $pannes;	
	}
	
	//Envoi des statuts d'ouvrage par SMS
	public function sendSms($message, $phoneNumber, $numPanne = null)
	{
		$config = Utils::getSmsGatewayConfig();
		$url = $config->sms_gw;//Config::get('sofie.smsGatewayUrl');
		$soa = $config->sms_soa;//Config::get('sofie.SOA');
		$status = null;
		//$port = "8080";
		//$phoneNumber = "59651580";
		//$phoneNumber = "07268570";
		$content = $url . "?SOA=" . $soa . "&DA=" . $phoneNumber . "&Modem=1&Content=" . urlencode($message);
		//echo $content;
		try
		{
			$status  = file_get_contents($content);
			if($status == '0' || $status == null)
			{
				$this->setSyncStatus($numPanne);
			}
		}
		catch(\Exception $e)
		{
			$status = $e->getMessage();
		}
		
		Utils::log(" Url : " . $content . " - statut : " . $status);
	}	
		
		
	public function manageDataSynchroCentraleRegionale()
	{
		$ouvrages = $this->getOuvrages($idRegion);
		$pannes = getPannes($idRegion);
		return array('ouvrages' => $ouvrages, 'pannes' => $pannes);
	}
	
	//Synchronisation de données site regional vers site central
	public function dataSynchroRegionaleCentrale($idRegion)
	{
		Utils::log("---------------------- Debut synchronisation de données provenant d'un site régional ----------------------");
		
		$status = array();
		$entities = array();
		$model = Input::get('model');
		$keyName = App::make($model)->getKeyName();
		$dataArray = Input::get('dataArray');
		$region =  $this->getRegion($idRegion);
		Utils::log(" Région de provenance : " . $region);
		Utils::log(" Entité traitée : " . $model);
		Utils::log(" Total de lignes à traiter : " . count($dataArray));
		
		foreach($dataArray as $data)
		{
			$state = array();
			Utils::log(" Données reçues : " . http_build_query($data));
			$state = $this->manageEntity($model, $data[$keyName], $data);
			Utils::log('Result : ' . http_build_query($state));
			$status[] = $state;
		}
		
		Utils::log("---------------------- Fin synchronisation de données provenant d'un site régional ----------------------");
		
		return Response::json($status);
			
	}
	
	public function postGenericEntity($region = null)
	{
		Utils::log("---------------------- Debut recuperation d'entités à partir d'un site régional ----------------------");
		
		//return $idRegion; exit;
		if(empty($model)) $model = Input::get('model');
		if(empty($region)) $region = Input::get('region');
		
		$function = 'get'.$model.'s'; 
		
		Utils::log(" Région de provenance : " . $this->getRegion($region));
		Utils::log(" Entité : " . $model);
				
		$response  = $this->$function($region);
		
		Utils::log(" Total de lignes à recuperer : " . count($response));
		
		if(count($response) > 0) Utils::log(" Data: " . urldecode(http_build_query($response)));
		
		Utils::log("---------------------- Fin recuperation d'entités à partir d'un site régional ----------------------");
		
		return Response::json($response);
	}
	
	public function getOuvrages($idRegion)
	{	
		//$query = Ouvrage::with('region','suiviphysicochimique');
		//$ouvrages = $query->where('IDregion',$idRegion)->where('sync','N')->get();
		$ouvrages = Ouvrage::where('IDregion',$idRegion)->where('sync','N')->get();	
		//$suivi = SuiviPhysicoChimique::where('IDOuvrage',$idRegion)->where('sync','N')->get();	
		//var_dump($ouvrages);
		return $ouvrages;
	}
	
	public function getSuiviPhysicoChimiques($idRegion)
	{	
		$suiviPhysicoChimiques = DB::Table('t_suiviphysicochimique')
		->join('t_ouvrage','t_ouvrage.IDOuvrage','=','t_suiviphysicochimique.IDOuvrage')
		->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
		->whereRAW("t_suiviphysicochimique.sync = 'N'")->get(array('t_suiviphysicochimique.*'));
		return $suiviPhysicoChimiques ;
	}
	
	public function getEssaisPompages($idRegion)
	{	
		$essaisPompages = DB::Table('t_essaispompage')
		->join('t_ouvrage','t_ouvrage.IDOuvrage','=','t_essaispompage.IDOuvrage')
		->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
		->whereRAW("t_essaispompage.sync = 'N'")->get(array('t_essaispompage.*'));
		return $essaisPompages ;
	}
	
	public function getCoupeGeologiques($idRegion)
	{	
		$coupeGeologiques = DB::Table('t_coupegeologique')
		->join('t_ouvrage','t_ouvrage.IDOuvrage','=','t_coupegeologique.IDOuvrage')
		->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
		->whereRAW("t_coupegeologique.sync = 'N'")->get(array('t_coupegeologique.*'));
		return $coupeGeologiques ;
	}
		
	public function getVenuEauPrincipales($idRegion)
	{	
		$venuEauPrincipales = DB::Table('t_venueauprincipale')
		->join('t_ouvrage','t_ouvrage.IDOuvrage','=','t_venueauprincipale.IDOuvrage')
		->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
		->whereRAW("t_venueauprincipale.sync = 'N'")->get(array('t_venueauprincipale.*'));
		return $venuEauPrincipales ;
	}
	
		
	public function getNumAppels($idRegion)
	{
		$numAppels = array();
		$getNumAppelAgent = $this->getNumAppelAgents($idRegion);
		$getNumAppelComite = $this->getNumAppelComites($idRegion);
		$getNumAppelReparateur = $this->getNumAppelReparateurs($idRegion);
		$numAppels = array_merge($getNumAppelAgent, $getNumAppelComite, $getNumAppelReparateur); 
		
		return $numAppels;		
	}
	
	public function getNumAppelAgents($idRegion)
	{
		$numAppelAgent = DB::Table('t_numeroappel')
		->join('t_agent','t_agent.IDNumAppel','=','t_numeroappel.IDNumAppel')
		->whereRAW('t_agent.IDRegion = ' . $idRegion)
		->whereRAW("t_numeroappel.sync = 'N'")->get(array('t_numeroappel.*'));
		return $numAppelAgent;
	}
	
	public function getNumAppelComites($idRegion)
	{
		$numAppelComites = DB::Table('t_numeroappel')
		->join('t_comite','t_comite.IDNumAppel','=','t_numeroappel.IDNumAppel')
		->join('t_localite','t_localite.IDLocalite','=','t_comite.IDLocalite')
		->whereRAW('t_localite.IDRegion = ' . $idRegion)
		->whereRAW("t_numeroappel.sync = 'N'")->get(array('t_numeroappel.*'));
		return $numAppelComites;
	}
	
	public function getNumAppelReparateurs($idRegion)
	{
		$numAppelReparateurs = DB::Table('t_numeroappel')
		->join('t_reparateur','t_reparateur.IDNumAppel','=','t_numeroappel.IDNumAppel')
		->join('t_localite','t_localite.IDReparateur','=','t_reparateur.IDReparateur')
		->whereRAW('t_localite.IDRegion = ' . $idRegion)
		->whereRAW("t_numeroappel.sync = 'N'")->get(array('t_numeroappel.*'));
		return $numAppelReparateurs;
	}	
	
	public function getProfiles($idRegion)
	{
		$profil = Profile::where('sync','N')->get();	
		return $profil;
	}
	
	public function getTypePannes($idRegion)
	{
		$typePannes = TypePanne::all();	
		return $typePannes;
	}
	
	
	public function getAgents($idRegion)
	{
		$agents = Agent::where('IDregion',$idRegion)->where('sync','N')->get();	
		return $agents;
	}
	
	public function getLocalites($idRegion)
	{
		$localites = Localite::where('IDregion',$idRegion)->where('sync','N')->get();	
		return $localites;
	}
	
	public function getComites($idRegion)
	{
		/*
		$comites = DB::Table('t_comite')
		->join('t_ouvrage','t_ouvrage.IDComite','=','t_comite.IDComite')
		->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
		->whereRAW("t_comite.sync = 'N'")->get(array('t_comite.*'));
		return $comites;
		*/
		$comites = DB::Table('t_comite')
		->join('t_localite','t_localite.IDLocalite','=','t_comite.IDLocalite')
		->whereRAW('t_localite.IDRegion = ' . $idRegion)
		->whereRAW("t_comite.sync = 'N'")->get(array('t_comite.*'));
		return $comites;
	}
	
	public function getReparateurs($idRegion)
	{
		$reparateurs = DB::Table('t_reparateur')
		->join('t_localite','t_localite.IDReparateur','=','t_reparateur.IDReparateur')
		->whereRAW('t_localite.IDRegion = ' . $idRegion)
		->whereRAW("t_reparateur.sync = 'N'")->get(array('t_reparateur.*'));
		return $reparateurs;
	}
	
	public function getCollectes($idRegion)
	{
		//$collectes = DB::select( DB::raw("SELECT * FROM t_collecte WHERE some_col = '$someVariable'") );
		
		$collectes = DB::Table('t_collecte')
		->join('t_ouvrage','t_ouvrage.IDOuvrage','=','t_collecte.IDOuvrage', 'right outer')
		->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
		->whereRAW("t_collecte.sync = 'N'")->get(array('t_collecte.*'));
		return $collectes;
	}
	
	public function getPannes($idRegion)
	{
		$pannes = DB::Table('t_panne')
		->join('t_ouvrage','t_ouvrage.IDOuvrage','=','t_panne.IDOuvrage')
		->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
		->whereRAW("t_panne.sync = 'N'")->get(array('t_panne.*'));
		return $pannes;
	}
	
	public function getNotifications($idRegion)
	{
		$notifications = DB::Table('t_notification')
		->join('t_panne','t_notification.IDPanne','=','t_panne.IDPanne')
		->join('t_ouvrage','t_ouvrage.IDOuvrage','=','t_panne.IDOuvrage')
		->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
		->whereRAW("t_notification.sync = 'N'")->get(array('t_notification.*'));
		return $notifications;
	}
	
	public function getAppelTelephoniques($idRegion)
	{
		$appels = DB::Table('t_appeltelephonique')
		->join('t_panne','t_appeltelephonique.IDPanne','=','t_panne.IDPanne')
		->join('t_ouvrage','t_ouvrage.IDOuvrage','=','t_panne.IDOuvrage')
		->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
		->whereRAW("t_appeltelephonique.sync = 'N'")->get(array('t_appeltelephonique.*'));
		return $appels;
	}
	
	public function notifyEntity()
	{
		if(empty($entity)) $entity = Input::get('entity');
		//if(empty($keyName)) $keyName = Input::get('keyName');
		if(empty($id)) $id = Input::get('id');	
		if(empty($delete)) $delete = Input::get('delete');	
		
		try 
		{
			$model = $entity::find($id);
			//var_dump($model); exit;
			if($model)
			{
				//$status = DB::table($entity)->where($keyName ,$id)->update(array('sync' => 'Y'));
					$model->sync = "Y";
					$status = $model->save();
					
				Utils::log("Notification de mise à jour de l'entité distance " . $entity . " valeur de la clé : " . $id . " - OK");					
				return Response::json($status);
			}
			else
			{
				Utils::log("Notification de mise à jour de l'entité distance " . $entity . " valeur de la clé : " . $id . " - KO [Entité non trouvée]");	
				return Response::json("Entite non trouvée");
			}
			
		}
		catch(\Exception $e)
		{
			Utils::log("Notification de mise à jour de l'entité distance " . $entity . " valeur de la clé : " . $id . " - KO [" . $e->getMessage() . "]");	
			return Response::json($e->getMessage());
		}
		
	}
	
	public function createEntity()
	{		
		$model = Input::get('model');
		$operation = 'CREATION';
		if($model == 'Ouvrage')
		{
			return $this->createOuvrage();
		}
		elseif($model == 'User')
		{
			return $this->createUser();
		}
		elseif($model == 'Groupe')
		{
			return $this->createGroup();
		}
		else
		{
			try
			{
				if (!class_exists($model))
				{
					 throw new \Exception("L'entité '" .$model. "' n'existe pas.");
				}
				$entityArray = Input::get('entityArray');
				$keyName = App::make($model)->getKeyName();
				$status = array();		
				$entity = new $model;
				$id = $this->getEntityKey($model);
				$entityArray[$keyName] = $id;
				
				$entity->fill($entityArray);
				$entity->sync = 'Y';
				$entity->save();
				$status = array('entity' => $model, 'id' => $id, 'operation' => $operation, 'status' => 'OK', 'error' => "");	
			}
			catch(\Exception $e)
			{
					$status = array('entity' => $model, 'operation' => $operation, 'status' => 'KO', 'error' => $e->getMessage() );
			}
			
			return  Response::json($status);
		}
	}	
	
	public function createGroup($model = 'Groupe')
	{
		$status = array();
		$operation = 'CREATION';
		$groupeArray = Input::get('groupeArray');
		$userArray = Input::get('userArray');
		$droitArray = Input::get('droitArray');
		
		try
		{
			$groupe = new Groupe;
			$keyName = App::make($model)->getKeyName();
			$idGroupe = $this->getEntityKey($model);
			$groupeArray[$keyName] = $idGroupe;				
			$groupe->fill($groupeArray);
			$groupe->sync = 'Y';
			$groupe->save();
			$this->createGroupDroitElements($idGroupe, $droitArray);		
			// $this->createMassUserGroupElements($idGroupe, $userArray);
			
			$status = array('entity' => $model, 'id' => $idGroupe, 'operation' => $operation, 'status' => 'OK', 'error' => "");	
		}
		
		catch(\Exception $e)
		{
			$status = array('entity' => $model, 'operation' => $operation, 'status' => 'KO', 'error' => $e->getMessage() );
		}
			
		return  Response::json($status);
		
	}
	
	public function createMassUserGroupElements($idGroup, $elements)
	{
		$model = 'UserGroup';
		if(is_array($elements) && count($elements) > 0)
		{	
			$idEntities = array();
			foreach($elements as $element)
			{
				try
				{
				$entity = new $model;
				$entity->groupe_id = $idGroup; 
				$entity->user_id = $element['user_id'];	;
				// $entity->fill($element);
				$entity->save();
				}
				catch(\Exception $e)
				{
					echo $e->getMessage() ;
				}
			}			
		}
		
	}
	
	public function createGroupDroitElements($idGroup, $elements)
	{
		$model = 'GroupeDroit';
		if(is_array($elements) && count($elements) > 0)
		{	
			$idEntities = array();
			foreach($elements as $element)
			{
				try
				{
				$entity = new $model;
				$entity->groupe_id = $idGroup; 
				$entity->droit_id = $element['droit_id'];	;
				// $entity->fill($element);
				$entity->save();
				}
				catch(\Exception $e)
				{
					echo $e->getMessage() ;
				}
			}			
		}
	
	}
	
	public function createUser($model = 'User')
	{
		$status = array();
		$operation = 'CREATION';
		$userArray = Input::get('userArray');
		$userGroupArray = Input::get('userGroupArray');
		//var_dump($userGroupArray); exit;
		try
		{
			$user = new User;
			$keyName = App::make($model)->getKeyName();
			$idUser = $this->getEntityKey($model);
			$userArray[$keyName] = $idUser;				
			$user->fill($userArray);
			$user->sync = 'Y';
			$user->save();			
			$this->createUserGroupElements($idUser, $userGroupArray);
			
			$status = array('entity' => $model, 'id' => $idUser, 'operation' => $operation, 'status' => 'OK', 'error' => "");	
		}
		
		catch(\Exception $e)
		{
			$status = array('entity' => $model, 'operation' => $operation, 'status' => 'KO', 'error' => $e->getMessage() );
		}
			
		return  Response::json($status);
		
	}
	
	public function createUserGroupElements($userId, $elements)
	{
		$model = 'UserGroup';
		if(is_array($elements) && count($elements) > 0)
		{	
			$idEntities = array();
			foreach($elements as $element)
			{
				try
				{
				$entity = new $model;
				$entity->groupe_id = $element['groupe_id'];	
				$entity->user_id = $userId;
				$entity->fill($element);
				$entity->save($element);
				}
				catch(\Exception $e)
				{
					echo $e->getMessage() ;
				}
				//$idEntities[] = array('position' => $order, 'insertedId' => $id);
			}
			//return $idEntities;
		}
		//else return null;
	}
	
	public function createOuvrage()
	{
		$status = array();
		$operation = 'CREATION';
		$model = 'Ouvrage';
		
		$ouvrageArray = Input::get('ouvrageArray');
		$essaisPompageArray = Input::get('essaisPompageArray');
		$coupeGeologiqueArray = Input::get('coupeGeologiqueArray');
		$suiviPhysicoChimiqueArray = Input::get('suiviPhysicoChimiqueArray');
		$equipementForageArray = Input::get('equipementForageArray');
		$venuEauPrincipaleArray = Input::get('venuEauPrincipaleArray');
		
		try
		{
			if (empty($ouvrageArray['IDRegion']) || $ouvrageArray['IDRegion'] == null)
			{
				 throw new \Exception("Code de la région absent de la requete");
			}			
			$ouvrage = new Ouvrage;
			$keyName = App::make($model)->getKeyName();
			$idOuvrage = $this->getEntityKey('Ouvrage');
			$ouvrageArray[$keyName] = $idOuvrage;				
			$ouvrage->fill($ouvrageArray);
			$ouvrage->sync = 'Y';
			$ouvrage->save();
			
			//$idOuvrage = DB::getPdo()->lastInsertId();			
			$ouvrage->CodeOuvrage = $ouvrage->IDRegion . '-' . $idOuvrage;
				
			$ouvrage->save();
						
			$this->ouvrage = $ouvrage;
			
			$idEssaisPompage = $this->createOuvrageElements('EssaisPompage', $essaisPompageArray);
			
			$idCoupeGeologique = $this->createOuvrageElements('CoupeGeologique', $coupeGeologiqueArray);
			
			$idSuiviPhysicoChimique = $this->createOuvrageElements('SuiviPhysicoChimique', $suiviPhysicoChimiqueArray);
			
			$idEquipementForage = $this->createOuvrageElements('EquipementForage', $equipementForageArray);
			
			$idVenuEauPrincipale = $this->createOuvrageElements('VenuEauPrincipale', $venuEauPrincipaleArray);
			
			$elementsID = array('idEssaisPompage' => $idEssaisPompage,
				  'idCoupeGeologique' => $idCoupeGeologique,
				  'idSuiviPhysicoChimique' => $idSuiviPhysicoChimique,
				  'idEquipementForage' => $idEquipementForage,
				  'idVenuEauPrincipale' => $idVenuEauPrincipale);
				  
			$status = array('entity' => 'Ouvrage', 'idOuvrage' => $idOuvrage, 'codeOuvrage' => $ouvrage->CodeOuvrage, 'elementsId' => $elementsID, 'operation' => $operation, 'status' => 'OK', 'error' => '' );
		}
		catch(\Exception $e)
		{
				$status = array('entity' => 'Ouvrage', 'operation' => $operation, 'status' => 'KO', 'error' => $e->getMessage() );
		}
		return  Response::json($status);
	}
	
	public function createOuvrageElements($model, $elements)
	{
		if(is_array($elements) && count($elements) >0)
		{	
			$idEntities = array();
			foreach($elements as $element)
			{
				$entity = new $model;
				$keyName = App::make($model)->getKeyName();
				$id = $this->getEntityKey($model);
				$element[$keyName] = $id;	
				$order = $element['position'];
				unset($element['position']);
				$entity->fill($element);
				$this->ouvrage->$model()->save($entity);
				$idEntities[] = array('position' => $order, 'insertedId' => $id);
			}
			return $idEntities;
		}
		else return null;
	}
	
	public function manageEntity($model, $id, $entityArray)
	{ 	
		
		$status = array();
		$operation = null;
		
		Utils::log("Recherche de la clé : " .  $id . " en base de données..." );
		
			$entity = $model::find($id);
			
			if(!$entity )
			{
				Utils::log("Clé : " . $id . " inexistante");				
				
				if(!empty($entityArray['deleted_at']))
				{
					$operation = 'DELETE';
					Utils::log("Ligne marquée pour suppression mais absente de la base locale aucune action effectuée");
					return array('ID' => $id, 'operation' => $operation, 'status' => 'OK', 'error' => "" );
					exit;
				}
				$entity = new $model;
						
				$operation = 'CREATION';
								
				Utils::log("Ajout des infos de la clé : " . $id . " sur le site local");
				
			}
			else
			{
				Utils::log("Clé : " . $id . " existante en base de données ");
				if(!empty($entityArray['deleted_at']))
				{
					try
					{
						Utils::log("Suppression des infos de la clé : " . $id . " sur le site local");
						
						$operation = 'DELETE';
						$entity->delete();
						$status = array('ID' => $id, 'operation' => $operation, 'status' => 'OK', 'error' => "" );
					}
					catch(\Exception $e)
					{
						$status = array('ID' => $id, 'operation' => $operation, 'status' => 'KO', 'error' => $e->getMessage() );
					}
					return $status;
					exit;
				}
				else
				{
					$operation = 'UPDATE';
					Utils::log("Mise à jour des infos de la clé : " . $id . " sur le site local");
				}
			}
			try
			{
				$entity->fill($entityArray);
				$entity->sync = 'Y';
				$entity->save();
			   	$status = array('ID' => $id, 'operation' => $operation, 'status' => 'OK', 'error' => "" );	
			}
			catch(\Exception $e)
			{
				$status = array('ID' => $id, 'operation' => $operation, 'status' => 'KO', 'error' => $e->getMessage() );
			}
		
		return $status;
	}
	
	public function sendEntityKey($model = null)
	{
		if(empty($model)) $model = Input::get('model');
		if(!empty($region)) 
		{
			//$region = Input::get('region'); // A decommenter en test ou prod
			//$region = 1; // A commenter en test ou prod
			$region =  $this->getRegion($region);
		}
		else
		{
			$region = 'Locale';
		}
				
		$key = null;
		Utils::log("---------------------- Debut d'attribution de clé à une entité ----------------------");
		Utils::log("Region de provenance : " . $region);
		Utils::log("Entité : " . $model);
		
		if($model == 'Ouvrage')
		{
			$key = $this->getOuvrageKey();
			$logKey = http_build_query($key);
			//$key = $key['idOuvrage'];
			//$key['elementsId'];
		}
		else
		{
			$key  = $this->getEntityKey($model);
			$logKey = $key;
		}
		
		Utils::log("Clé attribuée : " . $logKey );
		
		Utils::log("---------------------- Fin d'attribution de clé à une entité ----------------------");
		
		return $key;
	}
	
	public function getEntityKey($model = null)
	{
		if(empty($model)) $model = Input::get('model');
		
		$insertedId = DB::transaction(function() use($model)
		{
			$cle  = Cle::where('entity', $model)->lockForUpdate()->first();
			$nextId = $cle->lastInsertedId +1;
			$cle->lastInsertedId = $nextId;
			$cle->save();
			return $nextId;
		});
		
		return $insertedId;
	}
	
	public function getOuvrageKey()
	{
		$essaisPompageArray = Input::get('essaisPompageArray');
		$coupeGeologiqueArray = Input::get('coupeGeologiqueArray');
		$suiviPhysicoChimiqueArray = Input::get('suiviPhysicoChimiqueArray');
		$equipementForageArray = Input::get('equipementForageArray');
		$venuEauPrincipaleArray = Input::get('venuEauPrincipaleArray');
		
		$idOuvrage = $this->getEntityKey('Ouvrage');
		
		$idEssaisPompage = $this->getOuvrageElementsKey('EssaisPompage', $essaisPompageArray);
			
		$idCoupeGeologique = $this->getOuvrageElementsKey('CoupeGeologique', $coupeGeologiqueArray);
			
		$idSuiviPhysicoChimique = $this->getOuvrageElementsKey('SuiviPhysicoChimique', $suiviPhysicoChimiqueArray);
			
		$idEquipementForage = $this->getOuvrageElementsKey('EquipementForage', $equipementForageArray);
		
		$idVenuEauPrincipale = $this->getOuvrageElementsKey('VenuEauPrincipale', $venuEauPrincipaleArray);
			
		$elementsID = array('idEssaisPompage' => $idEssaisPompage,
				  'idCoupeGeologique' => $idCoupeGeologique,
				  'idSuiviPhysicoChimique' => $idSuiviPhysicoChimique,
				  'idEquipementForage' => $idEquipementForage,
				  'idVenuEauPrincipale' => $idVenuEauPrincipale);
				  
		$status = array('entity' => 'Ouvrage', 'idOuvrage' => $idOuvrage, 'elementsId' => $elementsID);
	
		return $status;
	}
	
	public function getOuvrageElementsKey($model, $numberOfKey)
	{
		if($numberOfKey > 0)
		{
			$entitiesId = array();
			
			for($i = 0; $i < $numberOfKey; $i++)
			{
				$entitiesId [] = $this->getEntityKey($model);
			}
			
			return $entitiesId;
		}
		else return null;
	}
	
	
	public function getRegion($idRegion)
	{	
	//echo idRegion; exit;
		$region = DB::Table('t_region')->whereRAW('t_region.IDRegion = ' . $idRegion)->get(array('NomRegion'));
		return $region[0]->NomRegion;
	}
	
	
	public function setSyncStatus($numPanne)
	{
		$panne = Panne::where('NumPanne', $numPanne)->first();
		if($panne)
		{
			$panne->syncStatus = 'Y';
			$panne->save();
		}
	}
	
	public function insertSMSInTable($reveiver, $msg, $numPanne)
	{
		$config = Utils::getSmsGatewayConfig();
		$soa = $config->sms_soa;		
		
		$status = null;
		$content ="SOA=" . $soa . "-DA=" . $reveiver . "-Content=" . urlencode($msg);
		
		$sql = "insert into t_sendsms (SMS_DATE,SENDER, RECEIVER, CONTENT, ORIGIN, STATUS) values (now(), '" . $soa . "', '" . $reveiver . "', '" . $msg . "', '3', '1')";
		if(DB::connection()->getPdo()->exec($sql))
		{
			$this->setSyncStatus($numPanne);
			Utils::log("Message envoyé : "  . $content);
			return true;
		}
		else return false;
		
	}
	
	/*
	public function setNotifications($idregion)
	
	public function setAppelTelephoniques($idregion)
	
	public function setCollectes($idregions)
	
	*/
	
	
	
	
	
}
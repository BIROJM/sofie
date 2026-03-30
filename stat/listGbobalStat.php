<?php
require_once(dirname(__FILE__).'/class/statGlobale.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');
//DB::loadDbConnection();

if(!empty($_GET['date_debut'])) StatGlobale::$periodeDebut = $_GET['date_debut'];
else StatGlobale::$periodeDebut = date('Y') .'-01-01';
if(!empty($_GET['date_fin'])) StatGlobale::$periodeFin = $_GET['date_fin'];
else StatGlobale::$periodeFin = date('Y') .'-12-31';
if(!empty($_GET['regions'])) StatGlobale::$region = $_GET['regions'];
else StatGlobale::$region ="";
if(!empty($_GET['localite'])) StatGlobale::$localite = $_GET['localite'];
else StatGlobale::$localite ="";



if(isset($_GET['viewLocalite'])) 
{	
	Stat::getLocalite(StatGlobale::$region);
	exit;
}

if(isset($_GET['getRegion'])) 
{	
	$regions = StatGlobale::getRegion();
	echo json_encode($regions);
	exit;
}

if(isset($_GET['viewEvolutionStatus']))
{
	$status = StatGlobale::getEvolutionStatus();
	
	//var_dump($status); 
	$panTab = array();
	$pecTab = array();
	$recTab = array();
	$repTab = array();
	$hdrTab = array();
	
	foreach($status as $st)
	{
		if($st['motifNotif'] == 2)
		{
			array_push($panTab, array('date' => $st['dateAppel'], 'nb' => $st['nb']));
		}
		elseif($st['motifNotif'] == 3)
		{
			array_push($pecTab, array('date' => $st['dateAppel'], 'nb' => $st['nb']));
		}
		elseif($st['motifNotif'] == 4)
		{
			array_push($recTab, array('date' => $st['dateAppel'], 'nb' => $st['nb']));
		}
		elseif($st['motifNotif'] == 5)
		{
			array_push($repTab,  array('date' => $st['dateAppel'], 'nb' => $st['nb']));
		}
		elseif($st['motifNotif'] == 6)
		{
			array_push($hdrTab, array('date' => $st['dateAppel'], 'nb' => $st['nb']));
		}
		
	}
	
	//var_dump($tab);
	
	echo json_encode(
					array("panne" => $panTab , "marche" => $repTab, "prisEncharge" => $pecTab, "reparationEnCours" => $recTab, "reparationHorsDelai" => $hdrTab	 
		 ));
	exit;
}


if(isset($_GET['viewRealTime']))
{
	$status = StatGlobale::getStatutOuvrage();
	
	$marche = 0;
	$panne = 0;
	$priseCharge = 0;
	$reparationEnCours = 0 ;
		
	//var_dump($status); exit;
	foreach($status as $st)
	{
		if($st['id'] == 1)
		{
			$marche = $st['nb'];
		}
		elseif($st['id'] == 2)
		{
			$panne = $st['nb'];
		}
		elseif($st['id'] == 3)
		{
			$priseCharge = $st['nb'];
		}
		elseif($st['id'] == 4)
		{
			$reparationEnCours = $st['nb'];
		}		
	
	}
	
	echo json_encode(
					array("nbrePanne" => $panne, "nbreMarche" => $marche, "nbrePrisEncharge" => $priseCharge, "nbreReparationEnCours" => $reparationEnCours		 
		 ));
	exit;
}

if(isset($_GET['viewGlobalStat'])) 
{
	//if(!empty(StatGlobale::$region)) echo 'test';

	$nbrePanne = StatGlobale::nbrePanneReparation(1);

	$nbreReparation = StatGlobale::nbrePanneReparation(4);
	
	$nbreMoyPanne = StatGlobale::nbreMoyenPanneReparation(1);

	$nbreMoyPanne = number_format($nbreMoyPanne, 2, '.', '');

	$nbreMoyReparation = StatGlobale::nbreMoyenPanneReparation(4);

	$nbreMoyReparation = number_format($nbreMoyReparation, 2, '.', '');

	$delaiMoyReparation = StatGlobale::delaiMoyReparation();

	$nbreReparationEnCours = StatGlobale::nbrePanneReparation(3);

	$nbrePriseCommande = StatGlobale::nbrePanneReparation(2);

	$nbreCmdHorsDelai = StatGlobale::cmdHorsDelai();


	echo json_encode(array("nbrePanne" => $nbrePanne, 
		  "nbreReparation" => $nbreReparation,
		  "nbreMoyPanne" => $nbreMoyPanne,
		  "nbreMoyReparation" => $nbreMoyReparation,
		  "delaiMoyReparation" => $delaiMoyReparation,
		  "nbreReparationEnCours" => $nbreReparationEnCours,
		  "nbrePriseCommande" => $nbrePriseCommande,
		  "nbreCmdHorsDelai" => $nbreCmdHorsDelai		
		 ));

	exit;	 
}


//
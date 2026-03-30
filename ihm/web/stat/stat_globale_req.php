<?php
require_once(dirname(__FILE__).'/class/statGlobale.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');
//DB::loadDbConnection();

if(!empty($_GET['date_debut'])) StatGlobale::$periodeDebut = $_GET['date_debut'];
else StatGlobale::$periodeDebut = '2000-01-01';
if(!empty($_GET['date_fin'])) StatGlobale::$periodeFin = $_GET['date_fin'];
else StatGlobale::$periodeFin = '2030-01-01';
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
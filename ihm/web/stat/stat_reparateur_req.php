<?php
session_start();
require_once(dirname(__FILE__).'/class/statReparateur.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');
//DB::loadDbConnection();

if(!empty($_GET['date_debut'])) StatReparateur::$periodeDebut = $_GET['date_debut'];
else StatReparateur::$periodeDebut = '2000-01-01';
if(!empty($_GET['date_fin'])) StatReparateur::$periodeFin = $_GET['date_fin'];
else StatReparateur::$periodeFin = '2030-01-01';
if(!empty($_GET['regions'])) StatReparateur::$region = $_GET['regions'];
else StatReparateur::$region ="";
if(!empty($_GET['localites'])) StatReparateur::$localite = $_GET['localites'];
else StatReparateur::$localite ="";


if(isset($_GET['getReparateurs'])) 
{	
	$reparateurs = StatReparateur::getReparateurs();
	echo json_encode($reparateurs);
	
	//$error = json_last_error_msg();
	//echo $error;
	//echo 'yes';
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
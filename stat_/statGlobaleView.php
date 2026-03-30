<?php
require_once(dirname(__FILE__).'/class/statGlobale.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');
//DB::loadDbConnection();

StatGlobale::$periodeDebut = '2014-01-04';
StatGlobale::$periodeFin = '2015-08-08';
StatGlobale::$region = 1;


var_dump(StatGlobale::TypologiePanne());

exit;
//StatGlobale::nbrePanneReparation(2);

//StatGlobale::nbreMoyenPanneReparation(2);
/*
if($_GET['regions']) 
{	
	StatGlobale::$region = $_GET['regions'];
	Stat::getLocalite(StatGlobale::$region);
}
*/

$nbrePanne = StatGlobale::nbrePanneReparation(1);

$nbreReparation = StatGlobale::nbrePanneReparation(4);

$nbreMoyPanne = StatGlobale::nbreMoyenPanneReparation(1);

$nbreMoyReparation = StatGlobale::nbreMoyenPanneReparation(4);

$delaiMoyReparation = StatGlobale::delaiMoyReparation();

$nbreReparationEnCours = StatGlobale::nbrePanneReparation(3);

$nbrePriseCommande = StatGlobale::nbrePanneReparation(2);

$nbreCmdHorsDelai = StatGlobale::cmdHorsDelai();

$typologiePanne = StatGlobale::typologiePanne();

echo json_encode(array("nbrePanne" => $nbrePanne, 
	  "nbreReparation" => $nbreReparation,
	  "nbreMoyPanne" => $nbreMoyPanne,
	  "nbreMoyReparation" => $nbreMoyReparation,
	  "delaiMoyReparation" => $delaiMoyReparation,
	  "nbreReparationEnCours" => $nbreReparationEnCours,
	  "nbrePriseCommande" => $nbrePriseCommande,
	  "nbreCmdHorsDelai" => $nbreCmdHorsDelai,
	  "typologiePanne" => $typologiePanne
	 ));

exit;	 

//
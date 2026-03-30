<?php
require_once(dirname(__FILE__).'/class/db.class.php');
DB::loadDbConnection();

//echo DB::getHostAdress();

$iprange = array('localhost', '127.0.0.1', '192.168.');
$ip='192.168.1.1';
foreach($iprange as $range)
{
	$str = strpos($ip, $range);
	echo $str;
	if($str !== false)
	{	
		echo $str;
		echo 'yes';
				break;
	}
	else 
	{	
				echo $str;
				echo 'no';
				break;
	}
}

exit;
if(!empty($_GET['date_debut'])) StatGlobale::$periodeDebut = $_GET['date_debut'];
else StatGlobale::$periodeDebut = '2000-01-01';
if(!empty($_GET['date_fin'])) StatGlobale::$periodeFin = $_GET['date_fin'];
else StatGlobale::$periodeFin = '2030-01-01';
if(!empty($_GET['regions'])) StatGlobale::$region = $_GET['regions'];
else StatGlobale::$region ="";

//StatGlobale::$periodeFin = '2015-08-08';
//StatGlobale::$region = 1;

//StatGlobale::nbrePanneReparation(2);

//StatGlobale::nbreMoyenPanneReparation(2);
/*
if($_GET['regions']) 
{	
	StatGlobale::$region = $_GET['regions'];
	Stat::getLocalite(StatGlobale::$region);
}
*/

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

//
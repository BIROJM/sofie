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
//
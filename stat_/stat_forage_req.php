<?php
session_start();
require_once(dirname(__FILE__).'/class/statForage.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');
//DB::loadDbConnection();

if(!empty($_GET['date_debut'])) StatForage::$periodeDebut = $_GET['date_debut'];
else StatForage::$periodeDebut = '2000-01-01';
if(!empty($_GET['date_fin'])) StatForage::$periodeFin = $_GET['date_fin'];
else StatForage::$periodeFin = '2030-01-01';
if(!empty($_GET['regions'])) StatForage::$region = $_GET['regions'];
else StatForage::$region ="";
if(!empty($_GET['localites'])) StatForage::$localite = $_GET['localites'];
else StatForage::$localite ="";


if(isset($_GET['getForages'])) 
{	
	$forages = StatForage::getForages();
	echo json_encode($forages);
	
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
<?php
session_start();
require_once(dirname(__FILE__).'/class/statComite.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');

if(!empty($_GET['date_debut'])) statComite::$periodeDebut = $_GET['date_debut'];
else statComite::$periodeDebut = '2000-01-01';
if(!empty($_GET['date_fin'])) statComite::$periodeFin = $_GET['date_fin'];
else statComite::$periodeFin = '2030-01-01';
if(!empty($_GET['regions'])) statComite::$region = $_GET['regions'];
else statComite::$region ="";
if(!empty($_GET['localite'])) statComite::$localite = $_GET['localite'];
else statComite::$localite ="";

if(isset($_GET['getComites'])) 
{	
	$comites = statComite::getComites();
	echo json_encode($comites);
	exit;
	
	//var_dump($reparateurs);
}

//
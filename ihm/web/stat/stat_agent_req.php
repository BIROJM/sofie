<?php
session_start();
require_once(dirname(__FILE__).'/class/statAgent.class.php');
require_once(dirname(__FILE__).'/class/stat.class.php');
//DB::loadDbConnection();

if(!empty($_GET['date_debut'])) StatAgent::$periodeDebut = $_GET['date_debut'];
else StatAgent::$periodeDebut = '2000-01-01';
if(!empty($_GET['date_fin'])) StatAgent::$periodeFin = $_GET['date_fin'];
else StatAgent::$periodeFin = '2030-01-01';
if(!empty($_GET['regions'])) StatAgent::$region = $_GET['regions'];
else StatAgent::$region ="";
if(!empty($_GET['localites'])) StatAgent::$localite = $_GET['localites'];
else StatAgent::$localite ="";

if(isset($_GET['getAgents'])) 
{	
	$agents = StatAgent::getAgentsFormen();
	echo json_encode($agents);
	exit;
}


if(isset($_GET['getSociologues'])) 
{	
	$agents = StatAgent::getSociologues();
	echo json_encode($agents);
	exit;
}

if(isset($_GET['getDr'])) 
{	
	$agents = StatAgent::getDr();
	echo json_encode($agents);
	exit;
}
//
<?php
require_once(dirname(__FILE__).'/db.class.php');

class Stat
{	
	public static $periodeDebut = null;
	
	public static $periodeFin = null;
	
	public static $region = null;
	
	public static function getLocalite($region)
	{
		$db = DB::loadDbConnection();
		$sql = "select IDLocalite, NomLocalite from t_localite ";
		if($region != 0)
		$sql .= " where IDRegion = " . $region;	
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		echo json_encode($lines);
			
		exit;		
	}
	
	public static function getForage($localite = null)
	{
		$db = DB::loadDbConnection();		
		$sql = "select IDOuvrage, CodeOuvrage from t_ouvrage ";
		if($localite != '0')
		$sql .= " where IDLocalite = " . $localite;	
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		//var_dump($lines);
		
		echo json_encode($lines);
			
		exit;		
	}
	
	public static function getReparateurByRegion($region)
	{
		$db = DB::loadDbConnection();
		$sql = "select IDReparateur, NomRep, PrenomsRep, IDNumAppel
				from t_reparateur, localite 
				left join t_localite on t_localite.IDReparateur = t_reparateur.IDReparateur";
		if($region != 0)
		$sql .= " where t_localite.IDRegion = " . $region;	
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		echo json_encode($lines);
			
		exit;		
	}
	
	public static function getReparateurByLocalite($localite)
	{
		$db = DB::loadDbConnection();
		$sql = "select IDReparateur, NomRep, PrenomsRep, IDNumAppel
				from t_reparateur, localite 
				left join t_localite on t_localite.IDReparateur = t_reparateur.IDReparateur";
		if($region != 0)
		$sql .= " where t_localite.IDLocalite = " . $localite;	
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		echo json_encode($lines);
			
		exit;		
	}
	
	
	public static function formatTime($time)
	{
		$finalTime = explode('.', $time);
		return $finalTime[0];
	}
			
}
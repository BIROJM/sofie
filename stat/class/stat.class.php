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
		if(isset($time))
		{
			$finalTime = explode('.', $time);
			//return $finalTime[0];
			list($hours)= explode(":",$finalTime[0]);
			return ceil($hours/24);
		}
	}
	
	public static function dateRange($startDate, $endDate)
	{
		if(isset($startDate) && isset($endDate))
		{
			$days = (strtotime($endDate) - strtotime($startDate));
			if($days == 0) return 1;
			else return $days/86400;
		}
	}
	
	public static function getRegionName($id)
	{
		$db = DB::loadDbConnection();
		$sql = "select NomRegion from t_region where IDRegion = " . $id;
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		return $lines[0]['NomRegion'];
	}
	
	public static function getLocaliteName($id)
	{
		$db = DB::loadDbConnection();
		$sql = "select NomLocalite from t_localite where IDLocalite = " . $id;
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		return $lines[0]['NomLocalite'];
	}
	
	public static function getReparateurName($id)
	{
		$db = DB::loadDbConnection();
		$sql = "select NomRep, PrenomsRep from t_reparateur where IDReparateur = " . $id;
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		return $lines[0]['NomRep'] . ' ' . $lines[0]['PrenomsRep'];
	}
	
	public static function getComiteName($id)
	{
		$db = DB::loadDbConnection();
		$sql = "select NomSecretaire, prenomsSecretaire from t_comite where IDComite = " . $id;
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		return $lines[0]['NomSecretaire'] . ' ' . $lines[0]['prenomsSecretaire'];
	}
	
	public static function getAgentName($id)
	{
		$db = DB::loadDbConnection();
		$sql = "select NomAgent, PrenomsAgent from t_agent where IDAgent = " . $id;
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		return $lines[0]['NomAgent'] . ' ' . $lines[0]['PrenomsAgent'];
	}
	
	public static function getForageCode($id)
	{
		$db = DB::loadDbConnection();
		$sql = "select CodeOuvrage from t_ouvrage where IDOuvrage = " . $id;
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		//var_dump($sql); exit;
		return $lines[0]['CodeOuvrage'];
	}
	
	public static function getDateFormat($date)
	{
		$tab = explode('-', $date);
		return $tab[2] .'/'. $tab[1] .'/'. $tab[0];
	}
	
	public static function efficacite($var1, $var2)
	{
		if($var2 != 0)
		{
			return ($var1 / $var2) * 100;
		}
		return 0;
	}
	
	public static function divide($var1, $var2)
	{
		if($var2 != 0)
		{
			return ($var1 / $var2);
		}
		return 0;
	}
	
	public static function average($var1, $var2)
	{
		if($var1 != 0)
		{
			return (($var1 - $var2) /$var1);
		}
		return 0;
	}
	
	public static function reactivite($var1, $var2)
	{
		if($var1 != 0)
		{
			return (($var1 - $var2) /$var1) * 100;
		}
		return 0;
	}
			
}
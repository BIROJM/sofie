<?php
require_once(dirname(__FILE__).'/db.class.php');

class StatComite
{	
	public static $periodeDebut = null;
	
	public static $periodeFin = null;
	
	public static $region;
	
	public static $listComites;

	public static $localite;	
	
	public static function getComites()
	{
		$db = DB::loadDbConnection();
		$sql = "select t_comite.IDComite, t_comite.IDNumAppel, t_comite.NomSecretaire, t_ouvrage.CodeOuvrage, t_comite.PrenomsSecretaire from t_comite
				left join t_ouvrage on t_ouvrage.IDComite = t_comite.IDComite ";				
		if(!empty(self::$region)) $sql .= " where t_ouvrage.IDRegion = " . self::$region;
		if(!empty(self::$localite)) $sql .= " and t_ouvrage.IDLocalite = " . self::$localite;
		$sql .= " group by t_comite.IDComite order by t_comite.NomSecretaire, t_comite.PrenomsSecretaire";
		//echo $sql;
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		//var_dump($lines );
		return $lines;
	}
	
	public static function nbreReparation($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql ="select count(t_appeltelephonique.IDNumAppel) as nb
		from t_appeltelephonique
		where t_appeltelephonique.MotifAppel = 2 
		and t_appeltelephonique.IDNumAppel = '" . $idNumAppel . "'
		and DateHeureAppel between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		
		//echo ($sql);
		$lines = $db->query($sql)->fetchColumn();
		return $lines;			
	}
	
		
	public static function nbrePriseCommande($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(t_appeltelephonique.IDNumAppel) as nb
				from t_appeltelephonique
				where t_appeltelephonique.IDNumAppel = '" . $idNumAppel . "'
				and t_appeltelephonique.MotifAppel = 2 
				and DateHeureAppel between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";		
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
		
	public static function nbreAvisPriseEnComptePanne($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(t_notification.IDNumAppel) as nb
				from t_notification 
				where t_notification.MotifNotif = 3
				and  t_notification.IDNumAppel = '" . $idNumAppel . "'
				and DateHeureNotif between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";		
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
		
	public static function nbrePannes($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(t_appeltelephonique.IDNumAppel) as nb 
				from t_appeltelephonique, t_comite 
				where t_appeltelephonique.IDNumAppel = t_comite.IDNumAppel 
				and t_appeltelephonique.MotifAppel = 1
				and t_comite. IDNumAppel= '" . $idNumAppel ."'  
				and DateHeureAppel between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";	
				//echo $sql;
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
	
	
		
}
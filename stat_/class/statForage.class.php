<?php

class StatForage
{	
	public static $periodeDebut = null;
	
	public static $periodeFin = null;
	
	public static $region;

	public static $localite;
		
	public static function getForages()
	{
		$db = DB::loadDbConnection();
		$sql = "select t_ouvrage.IDOuvrage, t_ouvrage.CodeOuvrage, t_ouvrage.typeOuvrage from t_ouvrage
		left join t_localite on t_localite.IDLocalite = t_ouvrage.IDLocalite where CodeOuvrage is not null and validated = 1 and t_ouvrage.deleted_at is null ";				
		if(!empty(self::$region)) $sql .= " and t_localite.IDRegion = " . self::$region;
		if(!empty(self::$localite)) $sql .= " and t_localite.IDLocalite = " . self::$localite;		
		//echo $sql;
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		return $lines;
	}
	
	public static function nbrePanne($idouvrage)
	{	
		$db = DB::loadDbConnection();
		$sql = "select count(IDPanne) from t_panne where IDOuvrage = " . $idouvrage;
		$sql .=" and DateApparution is not null and DateApparution between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		$lines = $db->query($sql)->fetchColumn();
		return $lines;		
	}
	
	public static function nbrePanneAll()
	{	
		$db = DB::loadDbConnection();
		$sql = "select count(IDPanne) from t_panne ";
		$sql .=" where DateApparution is not null and DateApparution between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		$lines = $db->query($sql)->fetchColumn();
		return $lines;		
	}
	
	public static function nbreReparation($idouvrage)
	{	
		$db = DB::loadDbConnection();
		$sql = "select count(IDPanne) from t_panne where IDOuvrage = " . $idouvrage;
		$sql .=" and DateReparation is not null and DateReparation between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		$lines = $db->query($sql)->fetchColumn();
		return $lines;		
	}
	
		
	public static function nbreMoyenPanne($idouvrage)
	{
		$db = DB::loadDbConnection();
		$sql = "select avg(IDPanne) from t_panne where IDOuvrage = " . $idouvrage;
		$sql .=" and DateApparution is not null and DateApparution between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		$lines = $db->query($sql)->fetchColumn();
		return $lines;	
		
		
		//return ($nbrePanne/ self::nbrePanneAll()) * 100;
	}
	
	public static function nbreMoyenReparation($idouvrage)
	{
		$db = DB::loadDbConnection();
		$sql = "select avg(IDPanne) from t_panne where IDOuvrage = " . $idouvrage;
		$sql .=" and DateReparation is not null and DateReparation between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		$lines = $db->query($sql)->fetchColumn();
		return $lines;	
	}
	
		
	public static function delaiMoyReparation($idouvrage)
	{
		$db = DB::loadDbConnection();
		$sql = "SELECT SEC_TO_TIME (AVG(TIME_TO_SEC(TIMEDIFF(DateReparation,DateDebutRep)))) as delai 
				FROM t_panne WHERE DateDebutRep is not null and DateReparation is not null
				and t_panne.IDOuvrage = " . $idouvrage ."";
		$sql .=" and DateDebutRep >= '" . self::$periodeDebut . "' and DateReparation <= '" . self::$periodeFin . "'";
		//echo $sql;
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
	public static function delaiMoyIndisponibilite($idouvrage)
	{
		$db = DB::loadDbConnection();
		$sql = "SELECT SEC_TO_TIME (AVG(TIME_TO_SEC(TIMEDIFF(DateReparation,DateApparution)))) as delai 
				FROM t_panne WHERE DateApparution is not null and DateReparation is not null
				and t_panne.IDOuvrage = " . $idouvrage ."";
		$sql .=" and DateApparution >= '" . self::$periodeDebut . "' and DateReparation <= '" . self::$periodeFin . "'";
		//echo $sql;
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
	public static function tauxRealisation($nbreReparation, $nbrePannes)
	{
		if($nbrePannes != 0)
		{
			return ($nbreReparation / $nbrePannes) * 100;
		}
		else return 0;
	}
}
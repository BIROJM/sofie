<?php

class StatForage
{	
	public static $periodeDebut = null;
	
	public static $periodeFin = null;
	
	public static $region;

	public static $localite;
	
	public static $id;
		
	public static function getForages()
	{
		$db = DB::loadDbConnection();
		$sql = "select t_ouvrage.IDOuvrage, t_ouvrage.CodeOuvrage, t_ouvrage.typeOuvrage from t_ouvrage
		left join t_localite on t_localite.IDLocalite = t_ouvrage.IDLocalite where CodeOuvrage is not null and t_ouvrage.deleted_at is null ";				
		if(!empty(self::$region)) $sql .= " and t_localite.IDRegion = " . self::$region;
		if(!empty(self::$localite)) $sql .= " and t_localite.IDLocalite = " . self::$localite;	
		if(!empty(self::$id)) $sql .= " and t_ouvrage.IDOuvrage = " . self::$id;
		//echo $sql;
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		return $lines;
	}
	
	public static function nbrePannes($idouvrage)
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
	
	public static function nbreReparations($idouvrage)
	{	
		$db = DB::loadDbConnection();
		$sql = "select count(IDPanne) from t_panne where IDOuvrage = " . $idouvrage;
		$sql .=" and DateReparation is not null and DateReparation between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		$lines = $db->query($sql)->fetchColumn();
		return $lines;		
	}
	
	public static function nbrePriseCommandeHorsDelai($idouvrage)
	{	
		$db = DB::loadDbConnection();
		$sql = "select count(distinct(t_notification.IDPanne)) from t_notification, t_panne
				where t_notification.IDPanne = t_panne.IDPanne and t_notification.motifNotif = 6 
				and t_panne.IDOuvrage = " . $idouvrage;
		$sql .= " and DateHeureNotif between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		
		$lines = $db->query($sql)->fetchColumn();
		return $lines;		
	}
	
	public static function nbreReparationHorsDelai($idouvrage)
	{	
		$db = DB::loadDbConnection();
		$sql = "select count(distinct(t_notification.IDPanne)) from t_notification, t_panne
				where t_notification.IDPanne = t_panne.IDPanne and t_notification.motifNotif = 9 
				and t_panne.IDOuvrage = " . $idouvrage;
		$sql .= " and DateHeureNotif between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		
		$lines = $db->query($sql)->fetchColumn();
		return $lines;		
	}
	
	public static function nbreCollectes($idouvrage)
	{	
		$db = DB::loadDbConnection();
		$sql = "select count(distinct(t_collecte.IDOuvrage)) from t_collecte
				where t_collecte.IDOuvrage = '" . $idouvrage . "'";
		$sql .= " and DateSaisie between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		
		$lines = $db->query($sql)->fetchColumn();
		return $lines;		
	}
	
	public static function delaiMoyReparation($idouvrage)
	{
		$db = DB::loadDbConnection();
		$sql = "SELECT HOUR(SEC_TO_TIME (AVG(TIME_TO_SEC(TIMEDIFF(DateReparation,DateDebutRep))))) as delai 
				FROM t_panne WHERE DateDebutRep is not null and DateReparation is not null
				and t_panne.IDOuvrage = '" . $idouvrage ."'";
		$sql .=" and DateDebutRep >= '" . self::$periodeDebut . "' and DateReparation <= '" . self::$periodeFin . "'";
		//echo $sql;
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
		
		exit;
		
	}
	
	public static function nbreMoyenPanne($idouvrage)
	{
		$db = DB::loadDbConnection();
		$sql = "select avg(IDPanne) from t_panne where IDOuvrage = " . $idouvrage;
		$sql .=" and DateApparution is not null and DateApparution between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		$lines = $db->query($sql)->fetchColumn();
		return $lines;			
	}
	
	public static function nbreMoyenReparation($idouvrage)
	{
		$db = DB::loadDbConnection();
		$sql = "select avg(IDPanne) from t_panne where IDOuvrage = " . $idouvrage;
		$sql .=" and DateReparation is not null and DateReparation between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		$lines = $db->query($sql)->fetchColumn();
		return $lines;	
	}
	
		
	public static function delaiMoyReparation_old($idouvrage)
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
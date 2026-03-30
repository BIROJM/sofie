<?php
require_once(dirname(__FILE__).'/db.class.php');

class StatGlobale
{	
	public static $periodeDebut = null;
	
	public static $periodeFin = null;
	
	public static $region;

	public static $localite;	
	
	//public $db = DB::loadDbConnection();		
	
	public static function nbrePanneReparation_old($typePanne)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(IDPanne) from t_panne where";
		$sql .= " statutPanne = " . $typePanne;
		if(!empty(self::$region)) 
		$sql .= " and t_panne.IDOuvrage = t_ouvrage.IDOuvrage and t_ouvrage.IDRegion = " . self::$region ;
		$sql .=" and DateApparution between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		
		$sql .= " ";
		//echo ($sql);
		$lines = $db->query($sql)->fetchColumn();;
		//$lines = $lines->fetchAll();
		return $lines;
		
		exit;		
	}
	
	
	
	public static function nbrePanneReparation($typePanne)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(t_appeltelephonique.IDPanne) from t_appeltelephonique, t_panne ";
		if(!empty(self::$region)) $sql .= ", t_ouvrage ";		
		$sql .= "where t_appeltelephonique.IDPanne = t_panne.IDPanne and MotifAppel = " . $typePanne;		
		if(!empty(self::$region)) 
		{
			$sql .= " and t_panne.IDOuvrage = t_ouvrage.IDOuvrage and t_ouvrage.IDRegion = " . self::$region ;
		}
		if(!empty(self::$localite))
		{		
			$sql .= " and t_ouvrage.IDLocalite = " . self::$localite ;
		}
		
		$sql .=" and DateHeureAppel between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		//echo ($sql);
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
		
				
	}
	
		
	public static function nbreMoyenPanneReparation($typePanne)
	{
		$db = DB::loadDbConnection();
		$sql = "select avg(moy.id)
				from (
				select count(t_appeltelephonique.IDPanne), MotifAppel as id from t_appeltelephonique, t_panne ";
				if(!empty(self::$region)) $sql .= ", t_ouvrage ";
				$sql .= " where	t_appeltelephonique.IDPanne = t_panne.IDPanne and MotifAppel = " . $typePanne;
		
		if(!empty(self::$region)) 
		{
			$sql .= " and t_panne.IDOuvrage = t_ouvrage.IDOuvrage and t_ouvrage.IDRegion = " . self::$region ;
		}
		if(!empty(self::$localite))
		{		
			$sql .= " and t_ouvrage.IDLocalite = " . self::$localite ;
		}
				
		$sql .=" and DateHeureAppel between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "') as moy";
		//echo ($sql);
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
		
		exit;
	}
	
		
	public static function delaiMoyReparation()
	{
		$db = DB::loadDbConnection();
		$sql = "SELECT HOUR(SEC_TO_TIME (AVG(TIME_TO_SEC(TIMEDIFF(DateReparation,DateDebutRep))))) as delai 
				FROM t_panne";
				if(!empty(self::$region)) $sql .= ", t_ouvrage ";
				$sql .= " WHERE DateDebutRep is not null and DateReparation is not null";
				
		if(!empty(self::$region)) 
		{
			$sql .= " and t_panne.IDOuvrage = t_ouvrage.IDOuvrage and t_ouvrage.IDRegion = " . self::$region ;
		}
		
		if(!empty(self::$localite))
		{		
			$sql .= " and t_ouvrage.IDLocalite = " . self::$localite ;
		}
		
		$sql .=" and DateDebutRep >= '" . self::$periodeDebut . "' and DateReparation <= '" . self::$periodeFin . "'";
		//echo $sql;
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
		
		exit;
		
	}
	
	public static function cmdHorsDelai($typeNotif = 6)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(t_notification.IDPanne) from t_notification, t_panne ";
		if(!empty(self::$region)) $sql .= ", t_ouvrage ";
		$sql .= " where	t_notification.IDPanne = t_panne.IDPanne and MotifNotif = " . $typeNotif;
				
		if(!empty(self::$region)) 
		{
			$sql .= " and t_panne.IDOuvrage = t_ouvrage.IDOuvrage and t_ouvrage.IDRegion = " . self::$region ;
		}
		if(!empty(self::$localite))
		{		
			$sql .= " and t_ouvrage.IDLocalite = " . self::$localite ;
		}
		
		$sql .=" and DateHeureNotif between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		//echo ($sql);
		$lines = $db->query($sql)->fetchColumn();;
		return $lines;
	}
	
	public static function getRegion()
	{	
		$db = DB::loadDbConnection();
		$sql =" select IDRegion, NomRegion from t_region order by NomRegion";
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		return $lines;		
	}
	
	public static function typologiePanne()
	{	
		$db = DB::loadDbConnection();
		$sql =" select count(t_panne.type_panne_id) as nb, t_type_panne.libelle from t_panne, t_type_panne ";
		if(!empty(self::$region)) $sql .= ", t_ouvrage ";
		$sql .=" where t_panne.type_panne_id = t_type_panne.id and t_panne.statutPanne = 1 ";
		if(!empty(self::$region)) 
		{
			$sql .= " and t_panne.IDOuvrage = t_ouvrage.IDOuvrage and t_ouvrage.IDRegion = " . self::$region ;
		}
		if(!empty(self::$localite))
		{		
			$sql .= " and t_ouvrage.IDLocalite = " . self::$localite ;
		}
			
		$sql .=" and t_panne.DateReparation between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		
		$sql .= " group by t_panne.type_panne_id";
		
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		return $lines;		
	}
		
}
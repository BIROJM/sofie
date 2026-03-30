<?php
require_once(dirname(__FILE__).'/db.class.php');

class StatAgent
{	
	public static $periodeDebut = null;
	
	public static $periodeFin = null;
	
	public static $id;
	
	public static $region;
	
	public static $listAgents;

	public static $localite;	
	
	public static function getAgentsFormen()
	{
		$db = DB::loadDbConnection();
		$sql = "select distinct(IDAgent), IDNumAppel, NomAgent, PrenomsAgent from t_agent 
				left join t_localite on t_localite.IDAgentForma = t_agent.IDAgent 
				where t_agent.IDNumAppel is not null and t_localite.IDAgentForma is not null and t_agent.deleted_at is null ";				
		if(!empty(self::$region)) $sql .= " and t_localite.IDRegion = " . self::$region;
		if(!empty(self::$localite)) $sql .= " and t_localite.IDLocalite = " . self::$localite;	
		if(!empty(self::$id)) $sql .= " and t_agent.IDAgent = " . self::$id;	
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		return $lines;
	}
	
	public static function getSociologues()
	{
		$db = DB::loadDbConnection();
		$sql = "select t_agent.IDAgent, t_agent.IDNumAppel, t_agent.NomAgent, t_agent.PrenomsAgent, t_agent.IDRegion, t_region.NomRegion 
		from t_agent, t_region 
		where Qualification = 4 and t_agent.IDRegion = t_region.IDRegion and t_agent.IDNumAppel is not null and t_agent.deleted_at is null";				
		if(!empty(self::$region)) $sql .= " and t_agent.IDRegion = " . self::$region; 
		$sql .= " order by t_agent.NomAgent, t_agent.PrenomsAgent asc";
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		return $lines;
	}
	
	
	public static function getDr()
	{
		$db = DB::loadDbConnection();
		$sql = "select t_agent.IDAgent, t_agent.IDNumAppel, t_agent.NomAgent, t_agent.PrenomsAgent, t_agent.IDRegion, t_region.NomRegion 
		from t_agent, t_region 
		where Qualification = 5 and t_agent.IDRegion = t_region.IDRegion and t_agent.IDNumAppel is not null and t_agent.deleted_at is null";				
		if(!empty(self::$region)) $sql .= " and t_agent.IDRegion = " . self::$region; 
		$sql .= " order by t_agent.NomAgent, t_agent.PrenomsAgent asc";
		$lines = $db->query($sql);
		$lines = $lines->fetchAll();
		return $lines;
	}
	
	public static function nbreReparation($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql ="select count(t_appeltelephonique.IDNumAppel) as nb
		from t_appeltelephonique
		where t_appeltelephonique.MotifAppel = 4 
		and t_appeltelephonique.IDNumAppel = '" . $idNumAppel . "'
		and DateHeureAppel between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		
		//echo ($sql);
		$lines = $db->query($sql)->fetchColumn();
		return $lines;			
	}
	
	public static function nbreMoyenReparation($idNumAppel)
	{
	
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
	
	public static function nbrePriseCharge($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(t_appeltelephonique.IDNumAppel) as nb
				from t_appeltelephonique
				where t_appeltelephonique.IDNumAppel = '" . $idNumAppel . "'
				and t_appeltelephonique.MotifAppel = 3
				and DateHeureAppel between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";		
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
	public static function nbrePriseCommandeHorsDelai($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql ="select count(t_notification.IDNumAppel) as nb
		from t_notification
		where t_notification.MotifNotif = 3 
		and t_notification.IDNumAppel = '" . $idNumAppel . "'
		and t_notification.IDPanne in 
		( 
			select distinct(IDPanne) from t_notification, t_numeroappel
			where MotifNotif = 6 
			and  t_notification.IDNumAppel = t_numeroappel.IDNumAppel and t_numeroappel.IDProfile = 4 
			and DateHeureNotif between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "')";
		
		//echo ($sql);
		$lines = $db->query($sql)->fetchColumn();
		return $lines;			
	}
	
	public static function nbreReparationHorsDelai($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql ="select count(t_notification.IDNumAppel) as nb
		from t_notification
		where t_notification.MotifNotif = 3 
		and t_notification.IDNumAppel = '" . $idNumAppel . "' 
		and t_notification.IDPanne in 
		( 
			select distinct(IDPanne) from t_notification, t_numeroappel
			where MotifNotif = 6 
			and  t_notification.IDNumAppel = t_numeroappel.IDNumAppel and t_numeroappel.IDProfile = 5 
			and DateHeureNotif between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "')";
		
		//echo ($sql);
		$lines = $db->query($sql)->fetchColumn();
		return $lines;			
	}
	
	
	public static function nbreAvisReparation($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(t_notification.IDNumAppel) as nb 
				from t_notification 
				where t_notification.MotifNotif = 5
				and  t_notification.IDNumAppel = '" . $idNumAppel . "'
				and DateHeureNotif between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";		
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
	public static function nbreTransfertAbsence($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(t_notification.IDNumAppel) as nb
				from t_notification 
				where t_notification.MotifNotif = 7 
				and  t_notification.IDNumAppel = '" . $idNumAppel . "'
				and DateHeureNotif between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
				//echo $sql;
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
	public static function nbrePanne($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(t_notification.IDNumAppel) as nb 
				from t_notification 
				where t_notification.IDNumAppel = '" . $idNumAppel . "'
				and t_notification.MotifNotif = 1
				and DateHeureNotif between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";		
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
	
	public static function palmares($tauxRealisation, $delaiMoyReparation)
	{
		if($delaiMoyReparation != 0)
		{
			return ($tauxRealisation / $delaiMoyReparation) * 100;
		}
		else return 0;
	}
			
			
	public static function delaiMoyReparation($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql = "SELECT HOUR(SEC_TO_TIME (AVG(TIME_TO_SEC(TIMEDIFF(DateReparation,DateDebutRep))))) as delai 
				FROM t_panne WHERE DateDebutRep is not null and DateReparation is not null
				and t_panne.IDPanne in ( select t_appeltelephonique.IDPanne from t_appeltelephonique where t_appeltelephonique.MotifAppel = 4 and t_appeltelephonique.IDNumAppel = '" . $idNumAppel ."')";
		$sql .=" and DateDebutRep >= '" . self::$periodeDebut . "' and DateReparation <= '" . self::$periodeFin . "'";
		//echo $sql;
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
		
		exit;
		
	}
	
	//Fonction pour Sociologue 
	public static function creationValide($userID)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(IDOuvrage) from t_ouvrage
				where validated_by = ". $userID . "
				and deleted_at is null and validated = 1 and validated_at between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";	
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
	public static function creationNonValide($idRegion)
	{
		$db = DB::loadDbConnection();		
		$sql = "select count(IDOuvrage) from t_ouvrage where (t_ouvrage.validated = 0 or t_ouvrage.validated IS NULL) and deleted_at is null ";
		if(!empty(self::$region)) $sql .= " and t_ouvrage.IDRegion = " . self::$region; 
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
	public static function suiviValide($userID)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(IDCollecte) from t_collecte 
		where validated = 1 and validated_by = ". $userID . "
		and validated_at between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
	public static function suiviNonValide($idRegion)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(t_collecte.IDOuvrage) from t_ouvrage, t_collecte 
		
		where t_ouvrage.IDOuvrage = t_collecte.IDOuvrage ";
		//and t_ouvrage.IDRegion = " . $idRegion .";
		if(!empty(self::$region)) $sql .= " and t_ouvrage.IDRegion = " . self::$region; 
		$sql .= " and (t_ouvrage.validated = 0 or t_ouvrage.validated IS NULL)";
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
	public static function nbrePriseCommandeHorsDelaiRegion($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql ="select count(t_notification.IDNumAppel) from t_notification
		where MotifNotif = 6 
		and  t_notification.IDNumAppel = '" . $idNumAppel . "'
		and DateHeureNotif between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
	public static function tauxRealisationSociologue($creationValide, $creationNonValide, $suiviValide, $suiviNonValide)
	{
		$nbreValide = $creationValide + $suiviValide;
		$nbreNonValide = $creationNonValide + $suiviNonValide;
		
		if($nbreValide != 0)
		{
			return ($nbreNonValide / $nbreValide) * 100;
		}
		else return 0;
	}
	
	//Fonction pour DR
	
	public static function nbreComiteEau()
	{
		$db = DB::loadDbConnection();
		$sql = "select count(IDComite) from t_comite 
				left join t_localite on t_localite.IDLocalite = t_comite.IDLocalite ";				
		if(!empty(self::$region)) $sql .= " where t_localite.IDRegion = " . self::$region;
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
}
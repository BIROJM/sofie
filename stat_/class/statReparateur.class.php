<?php
require_once(dirname(__FILE__).'/db.class.php');

class StatReparateur
{	
	public static $periodeDebut = null;
	
	public static $periodeFin = null;
	
	public static $id;
	
	public static $region;
	
	public static $listReparateurs;

	public static $localite;	
	
	public static function getReparateurs()
	{
		$db = DB::loadDbConnection();
		$sql = "select t_reparateur.IDReparateur, t_reparateur.IDNumAppel, t_reparateur.NomRep, t_reparateur.PrenomsRep from t_reparateur, t_localite ";
		//$sql = " left join t_localite on t_localite.IDReparateur = t_reparateur.IDReparateur
		$sql .= " where t_localite.IDReparateur = t_reparateur.IDReparateur  and t_reparateur.IDNumAppel is not null and t_reparateur.deleted_at is null ";
		if(!empty(self::$region)) $sql .= " and t_localite.IDRegion = " . self::$region;
		if(!empty(self::$localite)) $sql .= " and t_localite.IDLocalite = " . self::$localite;
		if(!empty(self::$id)) $sql .= " and t_reparateur.IDReparateur = " . self::$id;
		$sql .= " group by t_reparateur.IDReparateur order by t_reparateur.NomRep, t_reparateur.PrenomsRep";
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
		and t_appeltelephonique.IDPanne in 
		( 
			select distinct(IDPanne) from t_appeltelephonique where MotifAppel = 4 
			and IDPanne is not null and DateHeureAppel between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "')";
		
		//echo ($sql);
		$lines = $db->query($sql)->fetchColumn();
		return $lines;			
	}
	
	public static function nbreMoyenReparation($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql = "select avg(moy.id)
				from (
				select count(t_appeltelephonique.IDPanne) as id from t_appeltelephonique, t_panne ";
				if(!empty(self::$region)) $sql .= ", t_ouvrage ";
				$sql .= " where	t_appeltelephonique.IDPanne = t_panne.IDPanne and MotifAppel = 2";
		
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
				//	echo $sql;
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
	}
	
	public static function nbrePannes($idReparateur)
	{
		$db = DB::loadDbConnection();
		$sql = "select count(t_appeltelephonique.IDNumAppel) as nb 
				from t_appeltelephonique, t_comite 
				where t_appeltelephonique.IDNumAppel = t_comite.IDNumAppel 
				and t_appeltelephonique.MotifAppel = 1
				and t_comite.IDLocalite in ( select IDLocalite from t_localite where IDreparateur = '" . $idReparateur ."' ) 
				and DateHeureAppel between '" . self::$periodeDebut . "' and '" . self::$periodeFin . "'";	
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
	
	public static function palmares($tauxRealisation, $delaiMoyReparation)
	{
		if($delaiMoyReparation != 0)
		{
			return abs(($tauxRealisation / $delaiMoyReparation));
		}
		return 0;
	}
			
			
	public static function delaiMoyReparation($idNumAppel)
	{
		$db = DB::loadDbConnection();
		$sql = "SELECT SEC_TO_TIME (AVG(TIME_TO_SEC(TIMEDIFF(DateReparation,DateDebutRep)))) as delai 
				FROM t_panne WHERE DateDebutRep is not null and DateReparation is not null
				and t_panne.IDPanne in ( select t_appeltelephonique.IDPanne from t_appeltelephonique where t_appeltelephonique.MotifAppel = 2 and t_appeltelephonique.IDNumAppel = " . $idNumAppel .")";
		$sql .=" and DateDebutRep >= '" . self::$periodeDebut . "' and DateReparation <= '" . self::$periodeFin . "'";
		//echo $sql;
		$lines = $db->query($sql)->fetchColumn();
		return $lines;
		
		exit;
		
	}
				
}
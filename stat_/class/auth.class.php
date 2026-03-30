<?php
session_start();

require_once(dirname(__FILE__).'/db.class.php');

class Auth
{	
	public static $algo = "sha256";
	
	public static function getAuth($username, $password)
	{
		unset($_SESSION['roles']);
		
		$db = DB::loadDbConnection();
		$password = self::getCryptedPassword($password);
		$sql = "select u.id, d.role from users u, users_groupes ug, groupes_droits gd, t_droit d where u.username = '" . $username . "' and u.password = '" . $password . "' and u.is_active = 1
		 and u.id = ug.user_id and gd.groupe_id = ug.groupe_id and gd.droit_id = d.id and d.role like '%ROLE_STAT%' and gd.deleted_at is null 
		 and (ug.deleted_at is null)
		";
		try
		{
			$lines = $db->query($sql);
			$lines = $lines->fetchAll();
			$roles = array();
			foreach($lines as $line)
			{
				$roles[] =  $line['role'];
			}
			//var_dump($roles);			
			
			if(count($lines) > 0)
			{		
				$_SESSION['roles'] = $roles;
				return true;
			}
			else
			{
				return false;
			}
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
			return false;
		}
		
		
	}
	
	public static function getCryptedPassword($password)
	{
		return base64_encode(hash(self::$algo, $password, true));		
	}
			
}
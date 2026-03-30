<?php

class UserAuth 
{
	
	public static $algo = "sha256";
	public static $status = false;
	public static $code = 0; 
	public static $isValid = false;
	
    public static function check($username, $password) 
	{
       Session::forget('UserIsValid');
	   $userInfo = null;
	   $password = self::getCryptedPassword($password);
	   
	   $user = User::where('username', $username)->where('password', $password)->first();
	   
	   if($user)
	   {
			if($user->is_active != 0) 
			{	
				self::$status = "Utilisateur '" . $username. "' actif";
				self::$code = 2; 
				self::$isValid = true;
				$userInfo = array('username' => $user->username, 'id' => $user->id);
			}
			else 
			{
				self::$status = "Utilisateur '" . $username. "' désactivé";
				self::$code = 1;
			}	
		}
		
		else
		{
			self::$status = "Utilisateur '" . $username. "' inexistant";
			self::$code = 0;
		}
				
		return array('userStatus' => self::$status, 'userCode' => self::$code, 'userInfo' => $userInfo);
	}
	
	public static function getCryptedPassword($password)
	{
		return base64_encode(hash(self::$algo, $password, true));		
	}
	
	//Fonction de decodage du mot de passe envoyé par le mobile
	
	public static function decodePassword($password)
	{
		
	}
}
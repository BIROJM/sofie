<?php
include('Utils.php');
class UserAuth 
{
	
	public static $algo = "sha256";
	public static $status = false; 
	public static $code = 0; 
	public static $isValid = false;		
	
    public static function check($username, $password) 
	{
       Utils::$imei = Input::get('imei');
	   Utils::log("Verification identité utilisateur");
	   Utils::log("Username : " . $username);
	   
	   Session::forget('UserIsValid');
	   $userInfo = null;
	   $password = self::getCryptedPassword($password);
	   
	   $response = array();
	   
	   $user = User::where('username', $username)->where('password', $password)->where('deleted_at', null)->first();
	   
	   if($user)
	   {
			if($user->is_mobile != 1)
			{
				self::$status = "Utilisateur '" . $username. "' droit insuffisant";
				self::$code = 3;
			}
			else
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
		}
		
		else
		{
			self::$status = "Utilisateur '" . $username. "' inexistant";
			self::$code = 0;
		}
		$response = array('message' => self::$status, 'status' => self::$code, 'userInfo' => $userInfo);
		Utils::log("Result : " . urldecode(http_build_query($response)));
	    Utils::log("Fin verification identité utilisateur ");
		
		return $response;
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
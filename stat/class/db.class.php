<?php
session_start();
class DB
{	
	public static $configFile = "./xml/config.xml";
	
	public static $setting;
	
	public static $id;
	
		
	public static function connect($host, $dbName, $username, $password)
	{
		try
		{
			$dns = "mysql:host=" . $host . ";dbname=" . $dbName .";charset=utf8";
			$connection = new PDO( $dns, $username, $password);
			$connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			return $connection;
		}
		catch (Exception $e ) 
		{
			return false;
		}
	}
				
		
	public static function loadDbConnection()
	{
		try
		{
			self::$setting = simplexml_load_file(self::$configFile);
			$_SESSION['url_stat'] = (string)self::$setting->serverUri;
			$_SESSION['localIp'] = (string)self::$setting->localIp;
			/*
			if(self::getHostAdress() == false)
			{
				$_SESSION['localIp'] = (string)self::$setting->externalIp;
			}
			else
			{
				$_SESSION['localIp'] = (string)self::$setting->localIp;
			}
			*/
		}
		catch (Exception $e) 
		{
			exit;
		}
		
		return  self::connect(self::$setting->databaseServer, self::$setting->databaseName, self::$setting->databaseUser, self::$setting->databasePassword);
		//return self::$id;
	}
	
	public static function getHostAdress()
	{
		$iprange = array('localhost', '127.0.0.1', '192.168.');
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		//echo $ip; 
		foreach($iprange as $range)
		{
			if(strpos($ip, $range))
			{				
				return true;
			}
			else return false;
			
		}
		
	}
	
	public static function closeConnection()
	{
		self::$id = null;
	}	
}
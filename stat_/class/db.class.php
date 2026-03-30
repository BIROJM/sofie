<?php

class DB
{	
	public static $configFile = "./xml/config.xml";
	
	public static $setting;
	
	public static $id;
	
		
	public static function connect($host, $dbName, $username, $password)
	{
		try
		{
			$dns = "mysql:host=" . $host . ";dbname=" . $dbName ."";
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
		}
		catch (Exception $e) 
		{
			exit;
		}
		
		return  self::connect(self::$setting->databaseServer, self::$setting->databaseName, self::$setting->databaseUser, self::$setting->databasePassword);
		//return self::$id;
	}
	
	public static function closeConnection()
	{
		self::$id = null;
	}	
}
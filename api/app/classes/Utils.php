<?php

use Monolog\Logger;

use Monolog\Handler\StreamHandler;

class Utils 
{
    public static $user = null;
	
	public static $imei = null;
	
	public static function log($logMessage, $logFile = null) 
	{
        $line = "";
		$logFile = "log-" . date('dmY') . ".log";				
		$fopened = fopen(storage_path().'/logs/sofie/'.$logFile , 'a+');
		$line .=  "[".date('j/m/Y H:i:s')."][" . $_SERVER['REMOTE_ADDR'] . "] ";
		if(!empty(self::$user))
		{
			$line .= "[" . self::$user . "]";
		}
		
		if(!empty(self::$imei))
		{
			$line .= "[" . self::$imei . "]";
		}
		
		$line .= " " . $logMessage . "\n";
		fputs($fopened, $line);
		fclose($fopened);
	}

	public static function getEntityKey($model)
	{
		$insertedId = DB::transaction(function() use($model)
		{
			$cle  = Cle::where('entity', $model)->lockForUpdate()->first();
			$nextId = $cle->lastInsertedId +1;
			$cle->lastInsertedId = $nextId;
			$cle->save();
			return $nextId;
		});
		
		return $insertedId;
	}
	
	public static function getRegion($idRegion)
	{	
	//echo idRegion; exit;
		$region = DB::Table('t_region')->whereRAW('t_region.IDRegion = ' . $idRegion)->get(array('NomRegion'));
		return $region[0]->NomRegion;
	}
	
	public static function getSmsGatewayConfig()
	{
		$config = DB::Table('t_config')->first();
		return $config;
	}
	
	public static function getRegionalSmsGatewayNumber()
	{		
		$smsNumbers = DB::Table('t_region')->get(array('t_region.IDRegion', 't_region.NomRegion', 't_region.numero_modem'));
		return $smsNumbers;
	}
}
<?php

use Monolog\Logger;

use Monolog\Handler\StreamHandler;

class Logging 
{
    public static function write($logMessage) 
	{
        $logFile = "log_" . date('dmY') . ".log";				
		$fopened = fopen(storage_path().'/logs/'.$logFile , 'a+');
		fputs($fopened, "[".date('j/m/Y H:i:s')."][" . $_SERVER['REMOTE_ADDR'] . "][". Auth::user()->name ."] ". $logMessage . "\n");
		fclose($fopened);
		/*
		$view_log = new Logger('');
		$view_log->pushHandler(new StreamHandler(storage_path().'/logs/'.$logFile));
		$view_log->addInfo($message);
		//$view_log->addWarning($message);
		*/
    }
}
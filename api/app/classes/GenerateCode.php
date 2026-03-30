<?php

class GenerateCode 
{
	
	public static $codeEntity = null;
	
    public static function getCode($profile) 
	{
        self::$codeEntity = Code::where('profile', $profile)->where('status', 'N')->first();	
		return self::$codeEntity->code;
	}
	
	public static function markCode() 
	{
        self::$codeEntity->status = 'Y';
		self::$codeEntity->save();
    }
}
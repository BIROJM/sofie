<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 24/08/2015
 * Time: 10:13
 */

namespace Sofie\AdminBundle\Model;


class Logging
{

    protected static function getRootPath()
    {
        return self::getRootDir().'/'.'log_'.date('d-m-Y').'.log';
    }

    protected static function getRootDir()
    {
        return __DIR__.'/../../../../app/'.self::getUploadDir();
    }

    protected static function getUploadDir()
    {
        return 'logs';
    }

    public static function write($msg)
    {
        $prepend = '['.date('d-m-Y H:i:s').']['.$_SERVER['REMOTE_ADDR'].']';
        $openID = fopen(self::getRootPath(), 'a+');
        if($openID){
            fputs($openID, $prepend.''.trim($msg)."\r\n");
            fclose($openID);
        }
    }

    protected static function read()
    {

    }
}
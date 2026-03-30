<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 17/08/2015
 * Time: 16:37
 */

namespace Sofie\AdminBundle\Model;


class ConfigFile
{
    const ADD_GET_KEY = 'get_key';
    const ADD_GET_ENTITY = 'get_entity';

    protected static $path = 'config.json';

    protected static function getRootPath()
    {
        return self::getRootDir().'/'.self::$path;
    }

    protected static function getRootDir()
    {
        return __DIR__.'/../../../../app/'.self::getUploadDir();
    }

    protected static function getUploadDir()
    {
        return 'config/settings';
    }

    public static function saveAddMode(array $addModeContent)
    {
        $content = self::load();
        $content['add_mode'] = $addModeContent;
        self::save($content);
    }

    public static function loadAddMode()
    {
        $content = self::load();
        return $content['add_mode'];
    }

    public static function save(array $content)
    {
        if(is_writable(self::getRootDir())){
            file_put_contents(
                self::getRootPath(),
                json_encode($content, JSON_FORCE_OBJECT|JSON_PRETTY_PRINT|JSON_BIGINT_AS_STRING|JSON_UNESCAPED_SLASHES)
            );
        }
    }

    public static function load()
    {
        if(is_readable(self::getRootPath())){
            $content = json_decode(file_get_contents(self::getRootPath()), true);
            if(!array_key_exists('add_mode', $content)){
                $content['add_mode'] = array('mode'=>'get_key');
            }
            return $content;
        }
        return array('add_mode'=>array('mode'=>'get_key'));
    }

    public static function loadRegion()
    {
        $content = self::load();
        if(!array_key_exists('region', $content)) return null;
        return $content['region'];
    }
}
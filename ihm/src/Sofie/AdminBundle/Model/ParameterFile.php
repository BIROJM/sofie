<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 31/08/2015
 * Time: 09:39
 */

namespace Sofie\AdminBundle\Model;


use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class ParameterFile
{
    protected static $path = 'my_parameters.yml';

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
        return 'config';
    }

    public static function load()
    {
        if(is_readable(self::getRootPath())){
            try {
                $yaml = Yaml::parse(file_get_contents(self::getRootPath()));
                return $yaml;
            } catch (ParseException $e) {
                printf("Unable to parse the YAML string: %s", $e->getMessage());
            }
        }
        throw new \Exception('Impossible de charge les paramètres du site !');
    }

    public static function loadSite()
    {
        $value = self::load();
        if(is_array($value) && array_key_exists('sofie_site', $value['parameters'])){
            return $value['parameters']['sofie_site'];
        }
        throw new \Exception('Impossible de charge les paramètres du site !');
    }
}
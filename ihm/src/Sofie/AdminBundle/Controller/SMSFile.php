<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 24/07/2015
 * Time: 16:09
 */

namespace Sofie\AdminBundle\Controller;


class SMSFile
{
    protected  $path;

    public function __construct()
    {
        $this->path = 'sms_config.json';
    }

    protected function getRootPath()
    {
        return $this->getRootDir().'/'.$this->path;
    }

    protected function getRootDir()
    {
        return __DIR__.'/../../../../app/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'config/settings';
    }

    public function save(array $content)
    {
        if(is_writable($this->getRootDir())){
            file_put_contents(
                $this->getRootPath(),
                json_encode($content, JSON_FORCE_OBJECT|JSON_PRETTY_PRINT|JSON_BIGINT_AS_STRING|JSON_UNESCAPED_SLASHES)
            );
        }
    }

    public function load()
    {
        if(is_readable($this->getRootPath())){
            $content = json_decode(file_get_contents($this->getRootPath()), true);
            if(!array_key_exists('gw', $content)){
                $content['gw'] = '';
            }
            if(!array_key_exists('soa', $content)){
                $content['soa'] = '';
            }
            return $content;
        }
        return array('gw'=>'', 'soa'=>'');
    }
}
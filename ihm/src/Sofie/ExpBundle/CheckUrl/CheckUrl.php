<?php

namespace Sofie\ExpBundle\CheckUrl;

/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 07/08/2015
 * Time: 23:35
 */
class CheckUrl
{
    public function check($url)
    {
        /*$file_headers = @get_headers($url);
        if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }
        else {
            return true;
        }*/
        if (!$fp = curl_init($url)) return false;
        return true;
    }
}
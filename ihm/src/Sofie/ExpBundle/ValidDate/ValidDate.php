<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 01/07/2015
 * Time: 11:59
 */

namespace Sofie\ExpBundle\ValidDate;

class ValidDate {

    public function isDate( $date, $strict = true )
    {
        /*$Stamp = strtotime( $Str );
        if($Stamp){
            $Month = date( 'm', $Stamp );
            $Day   = date( 'd', $Stamp );
            $Year  = date( 'Y', $Stamp );
            return checkdate( $Month, $Day, $Year );
        }
        return false;*/
        $dateTime = \DateTime::createFromFormat('d/m/Y', $date);
        if ($strict) {
            $errors = \DateTime::getLastErrors();
            if (!empty($errors['warning_count'])) {
                return false;
            }
        }
        return $dateTime !== false;
    }
}
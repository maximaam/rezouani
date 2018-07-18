<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 13.04.18
 * Time: 15:49
 */

namespace App\Utils;

/**
 * Class StringHelper
 * @package App\Utils
 */
class StringHelper
{

    /**
     * @param $string
     * @return string
     */
    public static function createSlug($string): string
    {
        $string = str_replace(
            ['ß', 'ä', 'Ä', 'ü', 'Ü', 'ö', 'Ö', 'à', 'á', 'é', 'ô', 'ù', 'ú'],
            ['ss', 'ae', 'ae', 'ue', 'ue', 'oe', 'oe', 'a', 'a', 'e', 'o', 'u', 'u'], $string);

        $string = strtolower($string);
        $string = preg_replace("#[ \-\/\\\+\&]+#"," ",preg_replace("#[^ \-\/\+\\\&a-z0-9]#","",$string));
        $string = str_replace(" ","-", trim($string));

        if ('' === $string) {
            $string = '--';
        }

        return $string;
    }

}
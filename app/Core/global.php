<?php
/**
 * Created by PhpStorm.
 * User: Nikita
 * Date: 07.11.2019
 * Time: 21:39
 */


/**
 * Get value from env
 *
 * @param $search
 * @return |null
 */
function env($search)
{
    $env_file = array_filter(file(".env"), function ($item) {
        return trim($item);
    });
    foreach ($env_file as $string) {
        $key = explode("=", $string)[0];
        if ($key == $search) {
            return trim(explode("=", $string)[1]);
        }
    }
    return null;
}
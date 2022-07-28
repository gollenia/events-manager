<?php

/**
 * Polyfill the new PHP 8.1 array_is_list function
 * 
 * @param array $array
 * @return bool
 */
if (!function_exists('array_is_list'))
{
    function array_is_list(array $a)
    {
        return $a === [] || (array_keys($a) === range(0, count($a) - 1));
    }
}
<?php
if (!function_exists('array_is_list'))
{
    function array_is_list(array $a)
    {
        return $a === [] || (array_keys($a) === range(0, count($a) - 1));
    }
}
<?php

function array_get($array, $key, $default = null)
{
    return is_array($array) && array_key_exists($key, $array) ? $array[$key] : $default;
}


<?php

/**
 * Path of the app folder
 *
 * @param string|null $file
 * @return mixed
 */
function app_path(string $file = null)
{
    $path = dirname(__FILE__, 2) . '/app';
    if ($file) {
        $path .=  '/' . ltrim($file,'/');
    }

    return $path;
}

/**
 * Path of the web folder
 *
 * @param string|null $file
 * @return mixed
 */
function web_path(string $file = null)
{
    $path = dirname(__FILE__, 2) . '/web';
    if ($file) {
        $path .=  '/' . ltrim($file,'/');
    }

    return $path;
}
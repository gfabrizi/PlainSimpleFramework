<?php

/**
 * Path of the app folder
 *
 * @param string|null $file
 * @return mixed
 */
function app_path(string $file = null)
{
    $path = dirname(dirname(__FILE__)) . '/app';
    if ($file) {
        $path .=  '/' . $file;
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
    $path = dirname(dirname(__FILE__)) . '/web';
    if ($file) {
        $path .=  '/' . $file;
    }

    return $path;
}
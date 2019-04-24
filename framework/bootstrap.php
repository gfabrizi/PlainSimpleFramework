<?php

if (!isset($_ENV['APP_ENV'])) {
    $_ENV['APP_ENV'] = 'prod';
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/helpers.php';

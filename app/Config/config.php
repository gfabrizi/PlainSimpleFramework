<?php
// Base default configuration
$config['controllersNamespace'] = 'App\Controllers';
$config['defaultLayout'] = 'BaseLayout';
$config['viewsUri'] = '/Views';

// Can be one of 'sqlite' or 'mysql'
$config['dbType'] = 'sqlite';

// Configuration params for your MySql db
$dbConfig['username'] = 'root';
$dbConfig['password'] = 'pa55w0rd';
$dbConfig['host'] = 'mysql-lamp';
$dbConfig['dbName'] = 'db_test';
$dbConfig['port'] = 3306;

// Configuration params for your Sqlite db
$dbConfig['filename'] = '/db/database.sql';

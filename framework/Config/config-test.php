<?php
// Base default configuration
$config['controllersNamespace'] = 'gfabrizi\PlainSimpleFramework\Tests\stubs';
$config['defaultLayout'] = 'BaseLayout';
$config['viewsUri'] = '/../framework/Tests/views';

// Can be one of 'sqlite' or 'mysql'
$config['dbType'] = 'sqlite';

// Configuration params for your Sqlite db
$dbConfig['filename'] = 'memory';

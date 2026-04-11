<?php

foreach (['/tmp/views', '/tmp/logs', '/tmp/sessions', '/tmp/cache'] as $dir) {
    is_dir($dir) || mkdir($dir, 0755, true);
}

putenv('VIEW_COMPILED_PATH=/tmp/views');
putenv('LOG_CHANNEL=stderr');
putenv('CACHE_STORE=array');
putenv('SESSION_DRIVER=cookie');

$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/views';

// Load Laravel
require __DIR__ . '/../public/index.php';
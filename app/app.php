<?php
$start = microtime(true);
require_once __DIR__ . '/../vendor/autoload.php';

$config = [
    'debug' => true,
    'timer.start' => $start,
];

if (file_exists(__DIR__ . '/config.php')) {
    include __DIR__ . '/config.php';
}


$app = new Silex\Application($config);

include __DIR__ . '/routing.php';

return $app;

<?php

if (php_sapi_name() !== 'cli') {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use Droid\Droid;

$d = new Droid('empire.php', 'Nick');
$d->engageThruters();
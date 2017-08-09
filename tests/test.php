<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use registrarapi\enom\enom;
$enom = new enom('resellertest', 'N1ghteyes', 'reselltest');
$res = $enom->check(['SLD' => 'enom', 'TLDList' => 'com,co.uk,ninja,net,org,org.uk']);
print_r($res);
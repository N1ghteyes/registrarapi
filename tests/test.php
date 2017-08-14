<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use registrarapi\Client;
$client = new Client(['account' => 'resellertest', 'user' => 'N1ghteyes', 'password' => 'reselltest']);
$res = $client->enom->check(['SLD' => 'enom', 'TLDList' => 'com,co.uk,ninja,net,org,org.uk']);
print_r($res);
/*$res2 = $client->enom->GetAgreementPage();
print_r($res2);*/
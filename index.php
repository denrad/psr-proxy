<?php

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Request;

require_once 'vendor/autoload.php';

$hosts = [
    'alexfitness.65apps.dev2.ddemo.ru',
    'mobile.alexfitness.ru', // @todo https
];

$serverRequest = ServerRequest::fromGlobals();
$serverUri = ServerRequest::getUriFromGlobals();

$client = new \GuzzleHttp\Client();
$sendResponse = true;

foreach ($hosts as $host) {
    $uri = $serverUri->withHost($host)->withPort(80);

    $request = new Request($serverRequest->getMethod(), $uri);
    foreach ($serverRequest->getHeaders() as $key => $values) {
        foreach ($values as $value) {
            $request->withAddedHeader($key, $value);
        }
    }

    $request->withBody($serverRequest->getBody());
    $response = $client->send($request);
    if ($sendResponse) {
        $sendResponse = false;

        foreach ($response->getHeaders() as $key => $values) {
            foreach ($values as $value) {
                header("{$key}: {$value}");
            }
        }

        echo $response->getBody();
    }
}
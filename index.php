<?php

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Request;

require_once 'vendor/autoload.php';

$hosts = [
    'example.com',
    'example2.com',
];

$serverRequest = ServerRequest::fromGlobals();
$serverUri = ServerRequest::getUriFromGlobals();

$client = new \GuzzleHttp\Client();

foreach ($hosts as $host) {
    $uri = $serverUri->withHost($host)->withPort(80);
    echo $uri, PHP_EOL;

    $request = new Request($serverRequest->getMethod(), $uri);
    foreach ($serverRequest->getHeaders() as $key => list($value)) {
        $request->withAddedHeader($key, $value);
    }

    $request->withBody($serverRequest->getBody());
    $response = $client->send($request);
}

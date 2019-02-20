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

$logname = sprintf('logs/%u.%u.log', time(), rand(1, 1000));

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

        $headers = '';
        foreach ($response->getHeaders() as $key => $values) {
            foreach ($values as $value) {
                header("{$key}: {$value}");
                $headers .= "{$key}: {$value}\n";
            }
        }

        try {
            file_put_contents($logname, $headers . "\n" . $request->getBody());
        } catch (\Throwable $e) {

        }
        echo $response->getBody();
    }
}
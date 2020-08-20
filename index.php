<?php
require_once __DIR__ . '/vendor/autoload.php';

use Klein\Klein;

$router = new Klein();

$router->respond('GET', '/', function ($request, $response) {
    $response->code(200);
    $response->json([
        'status' => 'OK',
        'message' => 'welcome to sudoku solver'
    ]);
});

$router->dispatch();
<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Service\Sudoku;
use Klein\Klein;
use Klein\Request;
use Klein\Response;

$router = new Klein();

/*
 * Welcome request. nothing important
 */
$router->respond('GET', '/', function ($request, $response) {
    $response->code(200);
    $response->json([
        'status' => 'OK',
        'message' => 'welcome to sudoku solver'
    ]);
});

/**
 * Solve a sudoku
 */
$router->respond('POST', '/solve', function (Request $request, Response $response) {
    if (preg_match('/application\/json/', $request->headers()->get('Content-Type'))) {
        $body = json_decode($request->body());
        if (isset($body->grid)) {
            $sudoku = Sudoku::importString($body->grid);
            $sudoku->solve();
            $response->code(200);
            $response->json([
                'status' => 'OK',
                'data' => (string)$sudoku
            ]);
            return true;
        }
    }
    $response->code(400);
    $response->json([
        'status' => 'ERROR',
        'message' => 'Bad Request: please send grid as json. {"grid":"123456...789"}'
    ]);
});

$router->dispatch();
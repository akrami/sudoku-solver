<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Service\Sudoku;
use Klein\App;
use Klein\Klein;
use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$router = new Klein();

$router->respond(function (Request $request, Response $response, ServiceProvider $service, App $app) use ($router) {
    $app->register('twig', function () {
        $loader = new FilesystemLoader("templates");
        return new Environment($loader, ["debug" => true]);
    });
});

/*
 * Welcome request. nothing important
 */
$router->respond('GET', '/', function (Request $request, Response $response, ServiceProvider $service, App $app) {
    return $app->twig->render('home.html.twig', ["name" => "Shalgham"]);
});

/**
 * Solve a sudoku
 */
$router->respond('POST', '/api/solve', function (Request $request, Response $response) {
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
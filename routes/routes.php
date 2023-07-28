<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_URI'] === '/') {
    header('Location: /index.php');
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use Bramus\Router\Router;

$router = new Router();

$router->get('/hello', function () {
    echo 'Hello, this is a GET route!';
});

$router->run();

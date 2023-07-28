<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    require __DIR__ . "/../controllers/$class.php";
});

set_exception_handler("errorHandler::handleException");

header("Content-type: application/json; charset:UTF-8");
$parts = explode("/", $_SERVER["REQUEST_URI"]);

if ($parts[1] != "GET") {
    http_response_code(404);
    echo "hello";
    exit;
}

$id = $parts[2] ?? null;

$database = new Database("host.docker.internal", "api", "root", "password");

$gateway = new ApiGateway($database);

$controller = new ApiController($gateway);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);

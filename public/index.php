<?php

declare(strict_types=1);

require_once "../controllers/ApiController.php";
require_once "../controllers/errorHandler.php";
require_once "../models/ApiModel.php";
require_once "../controllers/Database.php";

set_exception_handler("errorHandler::handleException");

header("Content-type: application/json; charset:UTF-8");
$parts = explode("/", $_SERVER["REQUEST_URI"]);

if ($parts[1] != "posts" & $parts[1] != "post") {
    http_response_code(404);
    echo "hello";
    exit;
}

$id = $parts[2] ?? null;

$database = new Database("host.docker.internal", "api", "root", "password");

$gateway = new ApiGateway($database);

$controller = new ApiController($gateway);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);

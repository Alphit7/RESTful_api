<?php

class ApiController
{
    public function __construct(private ApiGateway $gateway)
    {
    }

    public function processRequest(string $method, ?string $id): void
    {
        $uri = filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL);
        $parts = explode("/", $uri);

        if ($id && $parts[1] === "post") {
            $this->processResourceRequest($method, $id);
        } elseif ($parts[1] === "posts" && !$id) {
            $this->processCollectionRequest($method);
        } elseif ($parts[1] === "posts" && $id) {
            http_response_code(404);
            echo "Resource not found";
            exit;
        } else {
            http_response_code(400);
            echo "Invalid request";
            exit;
        }
    }

    private function processResourceRequest(string $method, string $id): void
    {
        if (!in_array($method, ['GET', 'PUT', 'DELETE'])) {
            http_response_code(400);
            echo "Invalid method";
            exit;
        }

        $id = filter_var($id, FILTER_VALIDATE_INT);

        if ($id === false) {
            http_response_code(400);
            echo "Invalid ID";
            exit;
        }

        $resource = $this->gateway->getById($id);

        if (!$resource) {
            http_response_code(404);
            echo "Resource not found";
            exit;
        }

        if ($method === "GET") {
            echo json_encode($resource);
        } elseif ($method === "PUT") {
            $data = (array) json_decode(file_get_contents("php://input"), true);
            $this->gateway->editPost($data, $id);
            echo json_encode([
                "Message" => "Post updated",
                "id" => $id
            ]);
        } elseif ($method === "DELETE") {
            $this->gateway->deletePost($id);
            echo json_encode([
                "Message" => "Post deleted",
                "id" => $id
            ]);
        }
    }

    private function processCollectionRequest(string $method): void
    {
        if ($method === "GET") {
            echo json_encode($this->gateway->getAll());
        } elseif ($method === "POST") {
            http_response_code(405);
            echo "Method not allowed";
            exit;
        }
    }
}

<?php


class ApiController
{
    public function __construct(private ApiGateway $gateway)
    {
    }
    public function processRequest(string $method, ?string $id): void
    {
        $parts = explode("/", $_SERVER["REQUEST_URI"]);


        if ($id && $parts[1] === "post") {

            $this->processResourceRequest($method, $id);
        } elseif ($parts[1] === "posts" && !$id) {
            $this->processCollectionRequest($method);
        } elseif ($parts[1] === "posts" && $id) {
            http_response_code(404);
            echo "hello";
            exit;
        } else $this->processUnitRequest($method);
    }
    private function processResourceRequest(string $method, string $id): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getById($id));
                if ($this->gateway->getById($id) == false) {
                    http_response_code(204);
                    echo "This post doesn't exist";
                }
                break;
            case "PUT":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                if ($this->gateway->getById($id) == false) {
                    http_response_code(204);
                    echo "This post doesn't exist";
                } else {
                    $this->gateway->editPost($data, $id);
                    echo json_encode([
                        "Message" => "Post updated",
                        "id" => $id
                    ]);
                    break;
                }
            case "DELETE":
                if ($this->gateway->getById($id) == false) {
                    http_response_code(204);
                    echo "This post doesn't exist";
                } else {
                    $this->gateway->deletePost($id);
                    echo json_encode([
                        "Message" => "Post deleted",
                        "id" => $id
                    ]);
                    break;
                }
        }
    }
    private function processCollectionRequest(string $method): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;
            case "POST":
                http_response_code(404);
                echo "hello";
                exit;
        }
    }
    private function processUnitRequest(string $method): void
    {
        switch ($method) {
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $id = $this->gateway->createPost($data);

                http_response_code(201);
                echo json_encode([
                    "Message" => "Post created",
                    "id" => $id
                ]);
                break;
        }
    }
}

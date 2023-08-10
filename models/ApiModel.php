<?php

class ApiGateway
{

    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM posts";

        $stmt = $this->conn->query($sql);

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getById($id)
    {

        $sql = "SELECT * FROM posts WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function createPost(array $data): string
    {
        $sql = "INSERT INTO posts (title,body,author) Values (:title ,:body, :author)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':title', $data["title"], \PDO::PARAM_STR);
        $stmt->bindValue(':body', $data["body"], \PDO::PARAM_STR);
        $stmt->bindValue(':author', $data["author"], \PDO::PARAM_INT);

        $stmt->execute();
        return $this->conn->lastInsertId();
    }
    public function editPost(array $data, $id): void
    {
        $sql = "UPDATE posts SET title = :title, body = :body, author = :author, updated_at = now() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':title', $data["title"], \PDO::PARAM_STR);
        $stmt->bindValue(':body', $data["body"], \PDO::PARAM_STR);
        $stmt->bindValue(':author', $data["author"], \PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePost($id): void
    {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}

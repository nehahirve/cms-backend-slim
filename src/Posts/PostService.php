<?php
declare(strict_types=1);

namespace App\Posts;


use App\Pages\PageModel;
use PDO;
use PDOStatement;

class PostService {
    private PDO $pdo;

    /**
     * PostService constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->createDatabaseTable();
    }

    /**
     * @param string $query
     * @return bool|PDOStatement
     */
    private function prepare(string $query) {
        return $this->pdo->prepare($query);
    }

    public function getPosts(): array {
        $query = "select * from posts order by created_at desc";
        $statement = $this->prepare($query);
        $statement->execute();

        $array = array();
        $array['posts'] = array();

        while ($entry = $statement->fetchObject(PostModel::class)) {
            array_push($array['posts'], $entry);

        }
        return $array;
    }

    public function editPost(int $id, string $title, string $body, string $url): ?PostModel {
        $query = "update posts set title=:title, body=:body, url=:url where id=:id";
        $statement = $this->prepare($query);
        $this->title = htmlspecialchars(strip_tags($title)); // clean up so no spec. chars
        $this->body = htmlspecialchars(strip_tags($body));
        $this->url = $url;
        $statement->bindParam(":body", $this->body);
        $statement->bindParam(":title", $this->title);
        $statement->bindParam(":url", $this->url);
        $statement->bindParam(":id", $id);

        $statement->execute();

        return $this->getPost($id + 0);
    }

    public function getPost(int $id): ?PostModel {
        $query = "select * from posts where id=:id";
        $statement = $this->prepare($query);
        $statement->execute(compact('id'));
        return $statement->fetchObject(PostModel::class) ?: null;
    }

    public function deletePost(int $id): ?PostModel {
        $query = "delete from posts where id=:id";
        $statement = $this->prepare($query);
        $statement->execute(compact('id'));
        return $statement->fetchObject(PostModel::class) ?: null;
    }

    public function createPost(string $title, string $body, string $url): PostModel {
        $query = "insert into posts set body=:body, title=:title, url=:url";
        $statement = $this->prepare($query);
        $this->title = htmlspecialchars(strip_tags($title)); // clean up so no spec. chars
        $this->body = htmlspecialchars(strip_tags($body));
        $this->url = $url;
        $statement->bindParam(":body", $this->body);
        $statement->bindParam(":url", $this->url);
        $statement->bindParam(":title", $this->title);

        $statement->execute();

        $id = (int)$this->pdo->lastInsertId();
        return $this->getPost($id);
    }

    public function createDatabaseTable(): void {
        $ddl = <<<EOF

create table IF NOT EXISTS posts
(
    id         int auto_increment
        primary key,
    title      varchar(255)                       not null,
    body       text                               not null,
    created_at datetime default CURRENT_TIMESTAMP not null,
    url        text                               null
);



EOF;
        $this->pdo->exec($ddl);
    }
}

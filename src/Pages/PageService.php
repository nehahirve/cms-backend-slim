<?php
declare(strict_types=1);

namespace App\Pages;


use PDO;
use PDOStatement;

class PageService
{
    private PDO $pdo;

    /**
     * PostService constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->createDatabaseTable();
    }

    /**
     * @param string $query
     * @return bool|PDOStatement
     */
    private function prepare(string $query)
    {
        return $this->pdo->prepare($query);
    }

    public function getPosts(): array
    {
        $query = "select * from pages";
        $statement = $this->prepare($query);
        $statement->execute();

        $array = array();
        $array['pages'] = array();

        while ($entry = $statement->fetchObject(PageModel::class)) {
            array_push($array['pages'], $entry);

        }
        return $array;
    }

    public function editPost(int $id, string $title, string $body): ?PageModel
    {
        $query = "update pages set title=:title, body=:body where id=:id";
        $statement = $this->prepare($query);
        $this->title = htmlspecialchars(strip_tags($title)); // clean up so no spec. chars
        $this->body = htmlspecialchars(strip_tags($body));
        $statement->bindParam(":body", $this->body);
        $statement->bindParam(":title", $this->title);
        $statement->bindParam(":id", $id);

        $statement->execute();

        return $this->getPost($id + 0);
    }

    public function getPost(int $id): ?PageModel
    {
        $query = "select * from pages where id=:id";
        $statement = $this->prepare($query);
        $statement->execute(compact('id'));
        return $statement->fetchObject(PageModel::class) ?: null;
    }

    public function deletePost(int $id): ?PageModel {
        $query = "delete from pages where id=:id";
        $statement = $this->prepare($query);
        $statement->execute(compact('id'));
        return $statement->fetchObject(PageModel::class) ?: null;
    }

    public function createPost(string $title, string $body): PageModel
    {
        $query = "insert into pages set body=:body, title=:title";
        $statement = $this->prepare($query);
        $this->title = htmlspecialchars(strip_tags($title)); // clean up so no spec. chars
        $this->body = htmlspecialchars(strip_tags($body));

        $statement->bindParam(":body", $body);
        $statement->bindParam(":title", $title);

        $statement->execute();

        $id = (int)$this->pdo->lastInsertId();
        return $this->getPost($id);
    }

    public function createDatabaseTable(): void
    {
        $ddl = <<<EOF
create table IF NOT EXISTS pages
(
    id         int auto_increment
        primary key,
    title      varchar(255)                       not null,
    body       text                               null,
    created_at datetime default CURRENT_TIMESTAMP not null
);

EOF;
        $this->pdo->exec($ddl);
    }
}

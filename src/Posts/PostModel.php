<?php
declare(strict_types=1);

namespace App\Posts;


class PostModel {
    public int $id;
    public string $title;
    public ?string $body;
    public string $created_at;
    public ?string $url;
}

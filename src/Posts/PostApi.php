<?php
declare(strict_types=1);

namespace App\Posts;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

class PostApi
{
    /**
     * @var PostService
     */
    private PostService $postService;


    /**
     * PostApi constructor.
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function setup(Group $group)
    {
        $group->get('', function (Request $request, Response $response, $args)  {
            $response->getBody()->write(json_encode($this->postService->getPosts()));
            return $response->withHeader('Content-Type', 'application/json');
        });

        $group->post('', function (Request $request, Response $response, $args) {
            $input = json_decode(file_get_contents('php://input'));
            $title =  $input->title;
            $body = $input->body;
            $response->getBody()->write(json_encode($this->postService->createPost($title,$body)));
            return $response->withHeader('Content-Type', 'application/json');
        });
        $group->get('/{id}', function (Request $request, Response $response, $args) {
            $response->getBody()->write(json_encode($this->postService->getPost((int)$args['id']+0)));
            return $response->withHeader('Content-Type', 'application/json');
        });
        $group->put('/{id}', function (Request $request, Response $response, $args) {
            $input = json_decode(file_get_contents('php://input'));
            $title =  $input->title;
            $body = $input->body;
            $response->getBody()->write(json_encode($this->postService->editPost((int)$args['id']+0, $title, $body)));
            return $response->withHeader('Content-Type', 'application/json');
        });
        $group->delete('/{id}', function (Request $request, Response $response, $args) {
            $response->getBody()->write(json_encode($this->postService->deletePost((int)$args['id'])));
            return $response->withHeader('Content-Type', 'application/json');
        });
    }
}

<?php
declare(strict_types=1);

namespace App\Pages;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

class PageApi
{
    /**
     * @var PageService
     */
    private PageService $pageService;


    /**
     * PostApi constructor.
     * @param PageService $pageService
     */
    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function setup(Group $group)
    {
        $group->get('', function (Request $request, Response $response, $args)  {
            $response->getBody()->write(json_encode($this->pageService->getPosts()));
            return $response->withHeader('Content-Type', 'application/json');
        });

        $group->post('', function (Request $request, Response $response, $args) {
            $input = json_decode(file_get_contents('php://input'));
            $title =  $input->title;
            $body = $input->body;
            $response->getBody()->write(json_encode($this->pageService->createPost($title,$body)));
            return $response->withHeader('Content-Type', 'application/json');
        });
        $group->get('/{id}', function (Request $request, Response $response, $args) {
            $response->getBody()->write(json_encode($this->pageService->getPost((int)$args['id']+0)));
            return $response->withHeader('Content-Type', 'application/json');
        });
        $group->put('/{id}', function (Request $request, Response $response, $args) {
            $input = json_decode(file_get_contents('php://input'));
            $title =  $input->title;
            $body = $input->body;
            $response->getBody()->write(json_encode($this->pageService->editPost((int)$args['id']+0, $title, $body)));
            return $response->withHeader('Content-Type', 'application/json');
        });
        $group->delete('/{id}', function (Request $request, Response $response, $args) {
            $response->getBody()->write(json_encode($this->pageService->deletePost((int)$args['id'])));
            return $response->withHeader('Content-Type', 'application/json');
        });
    }
}

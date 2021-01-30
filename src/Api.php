<?php
declare(strict_types=1);

namespace App;

use PDO;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class Api {
    private App $app;

    /**
     * Api constructor.
     */
    public function __construct() {
        $this->app = AppFactory::create();

        $this->setup($this->app);
    }

    public function run() {
        $this->app->run();
    }

    function getDatabaseSettings(): DatabaseSettings {
        $settings = new DatabaseSettings();
        $settings->username = getenv('MYSQL_USERNAME');
        $settings->password = getenv('MYSQL_PASSWORD');
        $settings->host = getenv('MYSQL_HOST');
        $settings->database = getenv('MYSQL_DATABASE');
        $settings->sslCa = getenv('MYSQL_SSL_CA');
        $settings->sslKey = getenv('MYSQL_SSL_KEY');
        $settings->sslCert = getenv('MYSQL_SSL_CERT');

        $settings->sslCa = $this->storeInTemporaryFile($settings->sslCa);
        $settings->sslKey = $this->storeInTemporaryFile($settings->sslKey);
        $settings->sslCert = $this->storeInTemporaryFile($settings->sslCert);

//        if (!file_exists($settings->sslCa) || !file_exists($settings->sslKey) || !file_exists($settings->sslCert)) {
//            error_log("Missing MySQL SSL files");
//            die();
//        }

        return $settings;
    }

    private function setup(App $app): App {
        $pdo = (new PdoFactory($this->getDatabaseSettings()))->createPdo();

        $postsApi = new Posts\PostApi(new Posts\PostService($pdo));
        $pagesApi = new Pages\PageApi(new Pages\PageService($pdo));

        $app->options('/{routes:.*}', function (Request $request, Response $response) {
            // CORS Pre-Flight OPTIONS Request Handler
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS');
        });



        $app->group('/api/posts', function (Group $group) use ($postsApi) {
            $postsApi->setup($group);
        });

        $app->group('/api/pages', function (Group $group) use ($pagesApi) {
            $pagesApi->setup($group);
        });

        return $app;
    }

    private function storeInTemporaryFile(string $pathOrData)
    {
        if (file_exists($pathOrData)) {
            return $pathOrData;
        } else {
            $filePath = tempnam(sys_get_temp_dir(), 'mysql');
            file_put_contents($filePath, $pathOrData);
            return $filePath;
        }
    }
}

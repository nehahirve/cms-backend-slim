<?php
declare(strict_types=1);

use App\Api;

require __DIR__ . '/../../vendor/autoload.php';

echo getenv('MYSQL_SSL_CA');
echo getenv('MYSQL_SSL_KEY');
echo getenv('MYSQL_SSL_CERT');



(new Api())->run();


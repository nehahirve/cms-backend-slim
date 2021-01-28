<?php
declare(strict_types=1);

use App\Api;

require __DIR__ . '/../../vendor/autoload.php';

echo 'hello there, I am running';

(new Api())->run();

<?php

require dirname(__DIR__) . '/src/bootstrap.php';

use Tagin\ServiceContainer;

$container = new ServiceContainer();

$app = $container['app'];

require TAGIN_ROOT . '/src/routes.php';

$app->run();

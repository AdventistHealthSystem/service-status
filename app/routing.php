<?php

// Default route
$app->get('/', function() use ($app) {
    $controller = new App\Controller\DefaultController;
    return $controller->indexAction($app);
});

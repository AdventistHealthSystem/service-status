<?php

// Default route
$app->get('/', function() use ($app) {
    $controller = new App\Controller\DefaultController;
    $service = new App\Service\Apache;
    return $controller->indexAction($app, $service);
});

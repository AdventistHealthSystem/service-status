<?php

namespace App\Controller;

use Silex\Application;
use App\Service\Apache as Apache;

/**
 * Base controller. Shouldn't do much
 */
class DefaultController
{
    public function indexAction(\Silex\Application $app)
    {
        $service = new Apache;
        $response = [
            'version' => $service->getVersion(),
            'ips'     => $service->getIps(),
            'ports'   => $service->getPorts(),
            'sites'   => $service->getVhosts(),
        ];
        return $app->json($response, 200);
    }
}

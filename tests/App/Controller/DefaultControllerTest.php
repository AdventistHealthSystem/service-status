<?php

namespace App\Service\Tests;

use \Silex\Application as Application;
use \App\Service\Apache as Apache;
use \App\Controller\DefaultController as DefaultController;

class DefaultControllerTest extends \PHPUnit_Framework_TestCase
{
    const SUT_CLASS = '\App\Controller\DefaultController';

    /**
     * @dataProvider provideIndexAction
     */
    public function testIndexAction($expected, $version, $ips, $ports, $vhosts)
    {
        $app = new Application;
        $sut = new DefaultController;

        $service = $this->getMockBuilder('\App\Service\Apache')
            ->disableOriginalConstructor()
            ->setMethods(['getVersion', 'getIps', 'getPorts', 'getVhosts'])
            ->getMock();

        $service->expects($this->once())->method('getVersion')->will($this->returnValue($version));
        $service->expects($this->once())->method('getIps')->will($this->returnValue($ips));
        $service->expects($this->once())->method('getPorts')->will($this->returnValue($ports));
        $service->expects($this->once())->method('getVhosts')->will($this->returnValue($vhosts));

        $result = $sut->indexAction($app, $service);
        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\JsonResponse', $result);
        // $this->assertEquals($expected, $result);
    }

    public function provideIndexAction()
    {
        return [
            'simple test' => [
                'expected' => 'expected',
                'version'  => 'version value',
                'ips'      => 'ip value',
                'ports'    => 'port value',
                'vhosts'   => 'vhost value',
            ],
        ];
    }


    //     public function indexAction(\Silex\Application $app, Apache $service)
    //     {
    //         $response = [
    //             'version' => $service->getVersion(),
    //             'ips'     => $service->getIps(),
    //             'ports'   => $service->getPorts(),
    //             'sites'   => $service->getVhosts(),
    //         ];
    //         return $app->json($response, 200);
    //     }
    // }
}

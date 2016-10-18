<?php

namespace ServerStatus\Service\Tests;

use \ServerStatus\Service\Apache as Apache;

class ApacheTest extends \PHPUnit_Framework_TestCase
{
    const SUT_CLASS = '\ServerStatus\Service\Apache';

    protected function getSutMockWithoutConstructor($methods = [])
    {
        return $this->getMockBuilder(self::SUT_CLASS)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    protected function getSutMockWithConstructor($methods = [])
    {
        return $this->getMockBuilder(self::SUT_CLASS)
            ->setMethods($methods)
            ->getMock();
    }

    protected function getMethod($method)
    {
        $method = new \ReflectionMethod(self::SUT_CLASS, $method);
        $method->setAccessible(true);
        return $method;
    }

    protected function getProperty($property)
    {
        $property = new \ReflectionProperty(self::SUT_CLASS, $property);
        $property->setAccessible(true);
        return $property;
    }

    public function testConstructor()
    {
        $sut = $this->getSutMockWithoutConstructor([
            'initRawVersion',
            'initRawVhosts',
            'initRawModules',
            'initRawConfig'
        ]);

        $sut->expects($this->once())->method('initRawVersion');
        $sut->expects($this->once())->method('initRawVhosts');
        $sut->expects($this->once())->method('initRawModules');
        $sut->expects($this->once())->method('initRawConfig');

        $sut->__construct();
    }

    public function testInitRawVersion()
    {
        $expected = 'some output';
        $sut = $this->getSutMockWithConstructor(['runCommand']);

        $sut->expects($this->once())
            ->method('runCommand')
            ->with($this->equalTo(\ServerStatus\Service\Apache::CMD_DUMP_VERSION))
            ->will($this->returnValue($expected));

        $result = $this->getMethod('initRawVersion')->invoke($sut);
    }


    public function testInitRawVhosts()
    {
        $expected = 'some output';
        $sut = $this->getSutMockWithConstructor(['runCommand']);

        $sut->expects($this->once())
            ->method('runCommand')
            ->with($this->equalTo(Apache::CMD_DUMP_VHOSTS))
            ->will($this->returnValue($expected));

        $result = $this->getMethod('initRawVhosts')->invoke($sut);
    }

    public function testInitRawModules()
    {
        $expected = 'some output';
        $sut = $this->getSutMockWithConstructor(['runCommand']);

        $sut->expects($this->once())
            ->method('runCommand')
            ->with($this->equalTo(Apache::CMD_DUMP_MODULES))
            ->will($this->returnValue($expected));

        $result = $this->getMethod('initRawModules')->invoke($sut);
    }

    public function testInitRawConfig()
    {
        $expected = 'some output';
        $sut = $this->getSutMockWithConstructor(['runCommand']);

        $sut->expects($this->once())
            ->method('runCommand')
            ->with($this->equalTo(Apache::CMD_DUMP_RUN_CFG))
            ->will($this->returnValue($expected));

        $result = $this->getMethod('initRawConfig')->invoke($sut);
    }

    public function testGetVersion()
    {
        $expected = 'expected version';
        $version = 'raw version value';
        $sut = $this->getSutMockWithConstructor(['getValueByRegex']);

        $this->getProperty('rawVersion')->setValue($sut, $version);

        $sut->expects($this->once())
            ->method('getValueByRegex')
            ->with(
                $this->equalTo(Apache::REGEX_VERSION),
                $this->equalTo($version),
                $this->equalTo(1)
            )
            ->will($this->returnValue($expected));

        $result = $sut->getVersion();

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider provideGetIps
     */
    public function testGetIps($expected, $vhosts, $ips = [])
    {
        $sut = $this->getSutMockWithConstructor(['getValueByRegex']);

        $this->getProperty('rawVhosts')->setValue($sut, $vhosts);

        $sut->expects($this->once())
            ->method('getValueByRegex')
            ->with(
                $this->equalTo(Apache::REGEX_IP_ADDR),
                $this->equalTo($vhosts),
                $this->equalTo(0)
            )
            ->will($this->returnValue($ips));

        $result = $sut->getIps();

        $this->assertEquals($expected, $result);
    }

    public function provideGetIps()
    {
        return [
            'no ip values' => [
                'expected' => ['127.0.0.1'],
                'vhosts' => 'vhosts',
            ],

            'has ip values' => [
                'expected' => ['some value'],
                'vhosts' => 'vhosts',
                'ips' => ['some value'],
            ],
        ];
    }

    /**
     * @dataProvider provideGetPorts
     */
    public function testGetPorts($expected, $vhosts, $ports)
    {
        $sut = $this->getSutMockWithConstructor(['getValueByRegex']);

        $this->getProperty('rawVhosts')->setValue($sut, $vhosts);

        $sut->expects($this->once())
            ->method('getValueByRegex')
            ->with(
                $this->equalTo(Apache::REGEX_PORT),
                $this->equalTo($vhosts),
                $this->equalTo(1)
            )
            ->will($this->returnValue($ports));

        $result = $sut->getPorts();

        $this->assertEquals($expected, $result);

    }

    public function provideGetPorts()
    {
        return [
            'simple test' => [
                'expected' => [],
                'vhosts' => 'vhosts',
                'ports' => [],
            ],

            'has ports' => [
                'expected' => [80],
                'vhosts' => 'vhosts',
                'ports' => [80],
            ],
        ];
    }

    public function testGetVhosts()
    {
        $expected = 'expected';
        $vhosts = 'the vhost value';

        $sut = $this->getSutMockWithConstructor(['parseRawVhosts']);

        $this->getProperty('rawVhosts')->setValue($sut, $vhosts);

        $sut->expects($this->once())
            ->method('parseRawVhosts')
            ->with($this->equalTo($vhosts))
            ->will($this->returnValue($expected));

        $result = $sut->getVhosts();

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider provideParseRawVhosts
     */
    public function testParseRawVhosts($expected, $input = '')
    {
        $sut = $this->getSutMockWithoutConstructor();
        $result = $this->getMethod('parseRawVhosts')->invoke($sut, $input);
        $this->assertEquals($expected, $result);
    }

    public function provideParseRawVhosts()
    {
        return [
            'simple test' => [
                'expected' => [],
                'input' => '',
            ],
            'test with data' => [
                'expected' => [
                    'www.floridahospitalnetwork.com' => [
                        '10.57.205.206:443'
                    ],
                ],
                'input' => implode(PHP_EOL, [
                    'VirtualHost configuration:',
                    '10.57.205.206:443      is a NameVirtualHost',
                    'default server www.floridahospitalnetwork.com (/etc/httpd/vhosts/floridahospitalnetwork.com.conf:7)',
                    'port 443 namevhost www.floridahospitalnetwork.com (/etc/httpd/vhosts/floridahospitalnetwork.com.conf:7)',
                ]),
            ]
        ];
    }

    /**
     * @dataProvider provideGetModules
     */
    public function testGetModules($expected, $input)
    {
        $sut = $this->getSutMockWithConstructor([
            'initRawVersion',
            'initRawVhosts',
            'initRawModules',
            'initRawConfig',
            'parseRawModules',
        ]);

        $sut->expects($this->once())
            ->method('parseRawModules')
            ->with($this->equalTo($input))
            ->will($this->returnValue($expected));

        $this->getProperty('rawModules')->setValue($sut, $input);

        $result = $sut->getModules($input);
        $this->assertEquals($expected, $result);
    }

    public function provideGetModules()
    {
        return [
            'simple test' => [
                'expected' => [],
                'input'    => implode(PHP_EOL, [
                    'Loaded Modules:',
                     ' core_module (static)',
                     ' so_module (static)',
                     ' http_module (static)',
                     ' mpm_prefork_module (static)',
                     ' authn_file_module (shared)',
                     ' authn_core_module (shared)',
                     ' authz_host_module (shared)',
                ]),
            ],
        ];
    }

    /**
     * @dataProvider provideParseRawModules
     */
    public function testParseRawModules($expected, $input)
    {
        $sut = $this->getSutMockWithConstructor([
            'initRawVersion',
            'initRawVhosts',
            'initRawModules',
            'initRawConfig',
        ]);

        $result = $this->getMethod('parseRawModules')->invoke($sut, $input);

        $this->assertEquals($expected, $result);
    }

    public function provideParseRawModules()
    {
        return [
            'simple test' => [
                'expected' => [
                    'core_module',
                    'so_module',
                    'http_module',
                    'mpm_prefork_module',
                    'authn_file_module',
                    'authn_core_module',
                    'authz_host_module',
                ],
                'input'    => implode(PHP_EOL, [
                    'Loaded Modules:',
                     ' core_module (static)',
                     ' so_module (static)',
                     ' http_module (static)',
                     ' mpm_prefork_module (static)',
                     ' authn_file_module (shared)',
                     ' authn_core_module (shared)',
                     ' authz_host_module (shared)',
                ]),
            ],
        ];
    }

    /**
     * @dataProvider provideGetValueByRegex
     */
    public function testGetValueByRegex($expected, $pattern, $input, $index = 0)
    {
        $sut = $this->getSutMockWithoutConstructor();
        $result = $this->getMethod('getValueByRegex')->invoke($sut, $pattern, $input, $index);
        $this->assertEquals($expected, $result);
    }

    public function provideGetValueByRegex()
    {
        return [
            'no regex' => [
                'expected' => [''],
                'pattern'  => '//',
                'input'    => 'input value',
                'index'    => 0,
            ],

            'word regex' => [
                'expected' => ['input', 'value'],
                'pattern'  => '/(\w+)/',
                'input'    => 'input value',
                'index'    => 0,
            ],
        ];
    }
}

<?php

namespace ServerStatus\Tests\Service;

use \ServerStatus\Tests\AbstractTestCase;
use \ServerStatus\Service\Gitlab as Gitlab;

class GitlabTest extends AbstractTestCase
{
    const SUT_CLASS = '\ServerStatus\Service\Gitlab';

    public function testGetClient()
    {
        $sut = new Gitlab;
        $result = $this->getMethod(self::SUT_CLASS, 'getClient')->invoke($sut);
        $this->assertInstanceOf('\GuzzleHttp\Client', $result);
    }

    /**
     * @dataProvider provideSetOptions
     */
    public function testSetOptions($expected, $options)
    {
        $sut = new Gitlab;
        $result = $sut->setOptions($options);
        $this->assertEquals($sut, $result);

        $result = $this->getProperty(self::SUT_CLASS, 'options')->getValue($sut);
        $this->assertEquals($expected, $result);
    }

    public function provideSetOptions()
    {
        return [
            'simple test' => [
                'expected' => [
                    'gitlab-url'    => 'http://gitlab.com',
                    'private-token' => ''
                ],
                'options' => [],
            ],

            'test with one option' => [
                'expected' => [
                    'gitlab-url'    => 'http://gitlab.com',
                    'private-token' => '',
                    'some-option' => 'some-value',
                ],
                'options' => [
                    'some-option' => 'some-value',
                ],
            ],

            'test with defaults overridden' => [
                'expected' => [
                    'gitlab-url'    => 'some-value',
                    'private-token' => 'private-token-value',
                ],
                'options' => [
                    'gitlab-url' => 'some-value',
                    'private-token' => 'private-token-value',
                ],

            ]
        ];
    }

    /**
     * @dataProvider provideGetProjects
     */
    public function testGetProjects($body, $query = [])
    {
        $client = 'client-value';
        $url = 'url-value';
        $token = 'token-value';
        $expected = json_decode($body);
        $params = [
            'headers' => [
                'PRIVATE-TOKEN' => $token,
            ],
            'query' => $query,
        ];
        $sut = $this->getSutMockWithConstructor(self::SUT_CLASS, [
            'getClient', 'getUrl', 'getPrivateToken'
        ]);

        $client =  $this->getSutMockWithoutConstructor('GuzzleHttp\Client', [
            'request'
        ]);

        $response = $this->getSutMockWithoutConstructor('GuzzleHttp\Psr7\Response', [
            'getBody',
        ]);

        $response->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($body));

        $client->expects($this->once())
            ->method('request')
            ->with($this->equalTo('GET'), $this->equalTo($url), $this->equalTo($params))
            ->will($this->returnValue($response));

        $sut->expects($this->once())
            ->method('getClient')
            ->will($this->returnValue($client));

        $sut->expects($this->once())
            ->method('getUrl')
            ->will($this->returnValue($url));

        $sut->expects($this->once())
            ->method('getPrivateToken')
            ->will($this->returnValue($token));

        $result = $sut->getProjects();

        $this->assertEquals($expected, $result);

    }

    public function provideGetProjects()
    {
        return [
            'simple test' => [
                'body' => json_encode(['simple'=>'value']),
            ],
        ];
    }
}

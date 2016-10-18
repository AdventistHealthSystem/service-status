<?php

namespace ServerStatus\Tests;

class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getSutMockWithoutConstructor($class, $methods = [])
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    protected function getSutMockWithConstructor($class, $methods = [])
    {
        return $this->getMockBuilder($class)
            ->setMethods($methods)
            ->getMock();
    }

    protected function getMethod($class, $method)
    {
        $method = new \ReflectionMethod($class, $method);
        $method->setAccessible(true);
        return $method;
    }

    protected function getProperty($class, $property)
    {
        $property = new \ReflectionProperty($class, $property);
        $property->setAccessible(true);
        return $property;
    }
}

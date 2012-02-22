<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leach\Tests\Container;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Silex\Application;

use Leach\Container\SilexContainer;
use Leach\Test\TestCase;

class SilexContainerTest extends TestCase
{
    /**
     * @covers \Leach\Container\SilexContainer::__construct
     * @covers \Leach\Container\SilexContainer::getApplication
     */
    public function testSilexContainer()
    {
        $application = new Application();
        $container = new SilexContainer($application);
        $this->assertSame($application, $container->getApplication());
    }

    /**
     * @covers \Leach\Container\SilexContainer::handle
     */
    public function testHandle()
    {
        $application = new Application();

        $httpCache = $this->getHttpCacheMock();
        $application['http_cache'] = $httpCache;

        $httpCache
            ->expects($this->never())
            ->method('handle');

        $container = new SilexContainer($application);
        $this->assertInstanceOf(
            'Symfony\\Component\\HttpFoundation\\Response',
            $container->handle(new Request())
        );
    }

    /**
     * @covers \Leach\Container\SilexContainer::handle
     */
    public function testHandleWithHttpCache()
    {
        $request = new Request();
        $response = $this->getResponseMock();

        $application = new Application();

        $httpCache = $this->getHttpCacheMock();
        $application['http_cache'] = $httpCache;

        $httpCache
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->equalTo($request),
                $this->equalTo(HttpKernelInterface::MASTER_REQUEST),
                $this->equalTo(true)
            )
            ->will($this->returnValue($response));


        $container = new SilexContainer($application, array('use_http_cache' => true));
        $this->assertSame($response, $container->handle($request));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getHttpCacheMock()
    {
        return $this
            ->getMockBuilder('Symfony\\Component\\HttpKernel\\HttpCache\\HttpCache')
            ->disableOriginalConstructor()
            ->getMock();
    }
}

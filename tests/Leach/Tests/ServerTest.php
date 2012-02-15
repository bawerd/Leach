<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leach\Tests;

use Leach\Event\FilterResponseEvent;
use Leach\Events;
use Leach\Leach;
use Leach\Server;
use Leach\Test\TestCase;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

class ServerTest extends TestCase
{
    /**
     * @group server
     * @group constructor
     * @covers \Leach\Server::__construct
     * @covers \Leach\Server::getTransport
     * @covers \Leach\Server::getContainer
     */
    public function testServer()
    {
        $transport = $this->getTransportMock();

        $options = new ParameterBag(array(
            'expose_leach' => false,
            'max_requests' => 1
        ));

        $container = $this->getContainerMock();
        $container
            ->expects($this->once())
            ->method('getOptions')
            ->will($this->returnValue($options));
        $container
            ->expects($this->never())
            ->method('getEventDispatcher');

        $server = new Server($container, $transport);
        $this->assertSame($container, $server->getContainer());
        $this->assertSame($transport, $server->getTransport());

        $dispatcher = $this->getEventDispatcherMock();
        $dispatcher
            ->expects($this->once())
            ->method('addListener')
            ->with(
                $this->equalTo(Events::RESPONSE),
                $this->equalTo(array('Leach\\Server', 'exposeVersion'))
            );

        $options = new ParameterBag(array(
            'expose_leach' => true,
            'max_requests' => 1
        ));

        $container = $this->getContainerMock();
        $container
            ->expects($this->once())
            ->method('getOptions')
            ->will($this->returnValue($options));
        $container
            ->expects($this->once())
            ->method('getEventDispatcher')
            ->will($this->returnValue($dispatcher));

        $server = new Server($container, $transport);
    }

    /**
     * @group server
     * @group start
     * @covers \Leach\Server::start
     * @covers \Leach\Server::isRunning
     * @covers \Leach\Server::runOnce
     * @covers \Leach\Server::stop
     */
    public function testStart()
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock();

        $transport = $this->getTransportMock();
        $transport
            ->expects($this->once())
            ->method('connect');
        $transport
            ->expects($this->once())
            ->method('recv')
            ->will($this->returnValue($request));
        $transport
            ->expects($this->once())
            ->method('send');
        $transport
            ->expects($this->once())
            ->method('disconnect');

        $options = new ParameterBag(array(
            'expose_leach' => false,
            'max_requests' => 1
        ));

        $dispatcher = $this->getEventDispatcherMock();

        $container = $this->getContainerMock();
        $container
            ->expects($this->atLeastOnce())
            ->method('getEventDispatcher')
            ->will($this->returnValue($dispatcher));
        $container
            ->expects($this->atLeastOnce())
            ->method('handle')
            ->with($this->equalTo($request))
            ->will($this->returnValue($response));
        $container
            ->expects($this->atLeastOnce())
            ->method('getOptions')
            ->will($this->returnValue($options));

        $server = new Server($container, $transport);
        $server->start();
    }

    /**
     * @group server
     * @covers \Leach\Server::setUp
     * @covers \Leach\Server::filterRequest
     * @covers \Leach\Server::filterResponse
     * @covers \Leach\Server::tearDown
     */
    public function testEvents()
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock();

        $transport = $this->getTransportMock();
        $transport
            ->expects($this->any())
            ->method('connect');
        $transport
            ->expects($this->any())
            ->method('recv')
            ->will($this->returnValue($request));
        $transport
            ->expects($this->any())
            ->method('send');
        $transport
            ->expects($this->any())
            ->method('disconnect');

        $options = new ParameterBag(array(
            'max_requests' => 1
        ));

        $dispatcher = $this->getEventDispatcherMock();
        $dispatcher
            ->expects($this->at(0))
            ->method('dispatch')
            ->with($this->equalTo(Events::SETUP), $this->isInstanceOf('Leach\\Event\\SetUpEvent'));
                $dispatcher
            ->expects($this->at(1))
            ->method('dispatch')
            ->with($this->equalTo(Events::REQUEST), $this->isInstanceOf('Leach\\Event\\FilterRequestEvent'));
                    $dispatcher
            ->expects($this->at(2))
            ->method('dispatch')
            ->with($this->equalTo(Events::RESPONSE), $this->isInstanceOf('Leach\\Event\\FilterResponseEvent'));
        $dispatcher
            ->expects($this->at(3))
            ->method('dispatch')
            ->with($this->equalTo(Events::TEARDOWN), $this->isInstanceOf('Leach\\Event\\TearDownEvent'));
        $dispatcher
            ->expects($this->exactly(4))
            ->method('dispatch');

        $container = $this->getContainerMock();
        $container
            ->expects($this->atLeastOnce())
            ->method('getEventDispatcher')
            ->will($this->returnValue($dispatcher));
        $container
            ->expects($this->atLeastOnce())
            ->method('handle')
            ->with($this->equalTo($request))
            ->will($this->returnValue($response));
        $container
            ->expects($this->atLeastOnce())
            ->method('getOptions')
            ->will($this->returnValue($options));

        $server = new Server($container, $transport);
        $server->start();
    }

    /**
     * @covers \Leach\Server::exposeVersion
     */
    public function testExposeVersion()
    {
        $response = new Response();
        Server::exposeVersion(new FilterResponseEvent($this->getContainerMock(), $this->getRequestMock(), $response));
        $this->assertEquals(Leach::VERSION, $response->headers->get('X-Leach-Version'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getTransportMock()
    {
        return $this
            ->getMockBuilder('Leach\\Transport')
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->getMock();
    }
}

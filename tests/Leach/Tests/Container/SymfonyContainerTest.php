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

use Leach\Container\SymfonyContainer;
use Leach\Event;
use Leach\Events;
use Leach\Test\TestCase;

class SymfonyContainerTest extends TestCase
{
    /**
     * @covers \Leach\Container\SymfonyContainer::__construct
     * @covers \Leach\Container\SymfonyContainer::getKernel
     */
    public function testSymfonyContainer()
    {
        $kernel = $this->getKernelMock();
        $container = new SymfonyContainer($kernel);
        $this->assertSame($kernel, $container->getKernel());

        $kernel = $this->getTerminableMock();
        $container = new SymfonyContainer($kernel);
        $this->assertSame($kernel, $container->getKernel());
    }

    /**
     * @covers \Leach\Container\SymfonyContainer::handle
     */
    public function testHandle()
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock();

        $kernel = $this->getKernelMock();
        $kernel
            ->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($request))
            ->will($this->returnValue($response));

        $container = new SymfonyContainer($kernel);
        $this->assertInstanceOf('Symfony\\Component\\HttpFoundation\\Response', $container->handle($request));
    }

    /**
     * @covers \Leach\Container\SymfonyContainer::__construct
     */
    public function testBootEventListenerRegistration()
    {
        $kernel = $this->getKernelMock();

        $dispatcher = $this->getEventDispatcherMock();
        $dispatcher
            ->expects($this->once())
            ->method('addListener')
            ->with(
                $this->equalTo(Events::SETUP),
                $this->equalTo(array('Leach\\Container\\SymfonyContainer', 'boot')),
                $this->equalTo(0)
            );

        $container = new SymfonyContainer($kernel, array(
            // 'kernel_boot' => false,
            'kernel_terminate' => false,
            'kernel_shutdown' => false,
        ), $dispatcher);
    }

    /**
     * @covers \Leach\Container\SymfonyContainer::__construct
     */
    public function testTerminateEventListenerRegistration()
    {
        $kernel = $this->getTerminableMock();

        $dispatcher = $this->getEventDispatcherMock();
        $dispatcher
            ->expects($this->once())
            ->method('addListener')
            ->with(
                $this->equalTo(Events::TEARDOWN),
                $this->equalTo(array('Leach\\Container\\SymfonyContainer', 'terminate')),
                $this->equalTo(0)
            );

        $container = new SymfonyContainer($kernel, array(
            'kernel_boot' => false,
            // 'kernel_terminate' => false,
            'kernel_shutdown' => false,
        ), $dispatcher);
    }

    /**
     * @covers \Leach\Container\SymfonyContainer::__construct
     */
    public function testShutdownEventListenerRegistration()
    {
        $kernel = $this->getKernelMock();

        $dispatcher = $this->getEventDispatcherMock();
        $dispatcher
            ->expects($this->once())
            ->method('addListener')
            ->with(
                $this->equalTo(Events::TEARDOWN),
                $this->equalTo(array('Leach\\Container\\SymfonyContainer', 'shutdown')),
                $this->equalTo(0)
            );

        $container = new SymfonyContainer($kernel, array(
            'kernel_boot' => false,
            'kernel_terminate' => false,
             // 'kernel_shutdown' => false,
        ), $dispatcher);
    }

    /**
     * @covers \Leach\Container\SymfonyContainer::boot
     */
    public function testBoot()
    {
        $kernel = $this->getHttpKernelMock();
        $kernel
            ->expects($this->never())
            ->method('boot');

        $container = $this->getSymfonyContainerMock();
        $container
            ->expects($this->once())
            ->method('getKernel')
            ->will($this->returnValue($kernel));

        SymfonyContainer::boot(new Event\SetUpEvent($container));

        $kernel = $this->getMock('Symfony\\Component\\HttpKernel\\TerminableInterface');
        $kernel
            ->expects($this->never())
            ->method('boot');

        $container = $this->getSymfonyContainerMock();
        $container
            ->expects($this->once())
            ->method('getKernel')
            ->will($this->returnValue($kernel));

        SymfonyContainer::boot(new Event\SetUpEvent($container));

        $kernel = $this->getKernelMock();
        $kernel
            ->expects($this->once())
            ->method('boot');

        $container = $this->getSymfonyContainerMock();
        $container
            ->expects($this->once())
            ->method('getKernel')
            ->will($this->returnValue($kernel));

        SymfonyContainer::boot(new Event\SetUpEvent($container));
    }

    /**
     * @covers \Leach\Container\SymfonyContainer::shutdown
     */
    public function testShutdown()
    {
        $kernel = $this->getHttpKernelMock();
        $kernel
            ->expects($this->never())
            ->method('shutdown');

        $container = $this->getSymfonyContainerMock();
        $container
            ->expects($this->once())
            ->method('getKernel')
            ->will($this->returnValue($kernel));

        SymfonyContainer::shutdown(new Event\TearDownEvent(
            $container,
            $this->getRequestMock(),
            $this->getResponseMock()
        ));

        $kernel = $this->getMock('Symfony\\Component\\HttpKernel\\TerminableInterface');
        $kernel
            ->expects($this->never())
            ->method('shutdown');

        $container = $this->getSymfonyContainerMock();
        $container
            ->expects($this->once())
            ->method('getKernel')
            ->will($this->returnValue($kernel));

        SymfonyContainer::shutdown(new Event\TearDownEvent(
            $container,
            $this->getRequestMock(),
            $this->getResponseMock()
        ));

        $kernel = $this->getKernelMock();
        $kernel
            ->expects($this->once())
            ->method('shutdown');

        $container = $this->getSymfonyContainerMock();
        $container
            ->expects($this->once())
            ->method('getKernel')
            ->will($this->returnValue($kernel));

        SymfonyContainer::shutdown(new Event\TearDownEvent(
            $container,
            $this->getRequestMock(),
            $this->getResponseMock()
        ));
    }

    /**
     * @covers \Leach\Container\SymfonyContainer::terminate
     */
    public function testTerminate()
    {
        $kernel = $this->getHttpKernelMock();
        $kernel
            ->expects($this->never())
            ->method('terminate');

        $container = $this->getSymfonyContainerMock();
        $container
            ->expects($this->once())
            ->method('getKernel')
            ->will($this->returnValue($kernel));

        SymfonyContainer::terminate(new Event\TearDownEvent(
            $container,
            $this->getRequestMock(),
            $this->getResponseMock()
        ));

        $kernel = $this->getKernelMock();
        $kernel
            ->expects($this->never())
            ->method('terminate');

        $container = $this->getSymfonyContainerMock();
        $container
            ->expects($this->once())
            ->method('getKernel')
            ->will($this->returnValue($kernel));

        SymfonyContainer::terminate(new Event\TearDownEvent(
            $container,
            $this->getRequestMock(),
            $this->getResponseMock()
        ));


        $request = $this->getRequestMock();
        $response = $this->getResponseMock();

        $kernel = $this->getMock('Symfony\\Component\\HttpKernel\\TerminableInterface');
        $kernel
            ->expects($this->once())
            ->method('terminate')
            ->with(
                $this->equalTo($request),
                $this->equalTo($response)
            );

        $container = $this->getSymfonyContainerMock();
        $container
            ->expects($this->once())
            ->method('getKernel')
            ->will($this->returnValue($kernel));

        SymfonyContainer::terminate(new Event\TearDownEvent(
            $container,
            $request,
            $response
        ));
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testHttpKernelIsNotEnough()
    {
        $container = new SymfonyContainer($this->getHttpKernelMock());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSymfonyContainerMock()
    {
        return $this
            ->getMockBuilder('Leach\\Container\\SymfonyContainer')
            ->disableOriginalConstructor()
            ->getMock();
    }
}

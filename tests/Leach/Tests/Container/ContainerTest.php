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

use Leach\Events;
use Leach\Test\TestCase;
use Leach\Test\TestContainer;

class ContainerTest extends TestCase
{
    /**
     * @covers \Leach\Container\Container::__construct
     * @covers \Leach\Container\Container::getEventDispatcher
     * @covers \Leach\Container\Container::getOptions
     */
    public function testContainer()
    {
        $container = new TestContainer();
        $this->assertInstanceOf('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface', $container->getEventDispatcher());

        $options = $container->getOptions();
        $this->assertInstanceOf('Symfony\\Component\\HttpFoundation\\ParameterBag', $options);
        $this->assertEquals(array('max_requests' => 500), $options->all());

        $container = new TestContainer(array('foo' => 'bar'));
        $this->assertTrue($container->getOptions()->has('foo'));
        $this->assertEquals('bar', $container->getOptions()->get('foo'));

        $dispatcher = $this->getEventDispatcherMock();
        $container = new TestContainer(array(), $dispatcher);
        $this->assertSame($dispatcher, $container->getEventDispatcher());
    }

    /**
     * @covers \Leach\Container\Container::setUp
     */
    public function testSetUp()
    {
        $callback = function() {};

        $dispatcher = $this->getEventDispatcherMock();
        $dispatcher
            ->expects($this->once())
            ->method('addListener')
            ->with(
                $this->equalTo(Events::SETUP),
                $this->equalTo($callback),
                $this->equalTo(-512)
            );

        $container = new TestContainer(array(), $dispatcher);
        $container->setUp($callback, -512);
    }

    /**
     * @covers \Leach\Container\Container::tearDown
     */
    public function testTearDown()
    {
        $callback = function() {};

        $dispatcher = $this->getEventDispatcherMock();
        $dispatcher
            ->expects($this->once())
            ->method('addListener')
            ->with(
                $this->equalTo(Events::TEARDOWN),
                $this->equalTo($callback),
                $this->equalTo(512)
            );

        $container = new TestContainer(array(), $dispatcher);
        $container->tearDown($callback, 512);
    }
}

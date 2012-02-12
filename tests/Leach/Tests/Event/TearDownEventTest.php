<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leach\Tests\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Leach\Event\TearDownEvent;
use Leach\Test\TestCase;

class TearDownEventTest extends TestCase
{
    /**
     * @covers \Leach\Event\TearDownEvent::__construct
     * @covers \Leach\Event\TearDownEvent::getRequest
     * @covers \Leach\Event\TearDownEvent::getResponse
     */
    public function testTearDownEvent()
    {
        $container = $this->getMock('Leach\\Container\\ContainerInterface');
        $request = new Request();
        $response = new Response();
        $event = new TearDownEvent($container, $request, $response);
        $this->assertSame($container, $event->getContainer());
        $this->assertSame($request, $event->getRequest());
        $this->assertSame($response, $event->getResponse());
    }
}

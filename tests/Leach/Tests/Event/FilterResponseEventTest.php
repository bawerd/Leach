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

use Leach\Event\FilterResponseEvent;
use Leach\Test\TestCase;

class FilterResponseEventTest extends TestCase
{
    /**
     * @covers \Leach\Event\FilterResponseEvent::__construct
     * @covers \Leach\Event\FilterResponseEvent::getResponse
     */
    public function testEvent()
    {
        $container = $this->getMock('Leach\\Container\\ContainerInterface');
        $request = $this->getRequestMock();
        $response = $this->getResponseMock();
        $event = new  FilterResponseEvent($container, $request, $response);
        $this->assertSame($container, $event->getContainer());
        $this->assertSame($request, $event->getRequest());
        $this->assertSame($response, $event->getResponse());
    }
}

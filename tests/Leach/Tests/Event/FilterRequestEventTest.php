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

use Leach\Event\FilterRequestEvent;
use Leach\Test\TestCase;

class FilterRequestEventTest extends TestCase
{
    /**
     * @covers \Leach\Event\FilterRequestEvent::__construct
     * @covers \Leach\Event\FilterRequestEvent::getRequest
     */
    public function testEvent()
    {
        $container = $this->getMock('Leach\\Container\\ContainerInterface');
        $request = $this->getRequestMock();
        $event = new FilterRequestEvent($container, $request);
        $this->assertSame($container, $event->getContainer());
        $this->assertSame($request, $event->getRequest());
    }
}

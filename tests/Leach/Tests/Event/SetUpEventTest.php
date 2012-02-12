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

use Leach\Event\SetUpEvent;
use Leach\Test\TestCase;

class SetUpEventTest extends TestCase
{
    /**
     * @covers \Leach\Event\Event::__construct
     * @covers \Leach\Event\Event::getContainer
     */
    public function testEvent()
    {
        $container = $this->getMock('Leach\\Container\\ContainerInterface');
        $event = new SetUpEvent($container);
        $this->assertSame($container, $event->getContainer());
    }
}

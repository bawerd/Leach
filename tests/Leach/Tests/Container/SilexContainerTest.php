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

        $dispatcher = $this->getEventDispatcherMock();
        $application['dispatcher'] = $dispatcher;

        $container = new SilexContainer($application);
        $this->assertSame($application, $container->getApplication());
        $this->assertSame($dispatcher, $container->getEventDispatcher());
    }

    /**
     * @covers \Leach\Container\SilexContainer::handle
     */
    public function testHandle()
    {
        $container = new SilexContainer(new Application());
        $this->assertInstanceOf(
            'Symfony\\Component\\HttpFoundation\\Response',
            $container->handle($this->getRequestMock())
        );
    }
}

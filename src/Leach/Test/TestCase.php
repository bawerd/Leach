<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leach\Test;

/**
 * @codeCoverageIgnore
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getContainerMock()
    {
        return $this->getMock('Leach\\Container\\ContainerInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEventDispatcherMock()
    {
        return $this->getMock('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getHttpKernelMock()
    {
        return $this->getMock('Symfony\\Component\\HttpKernel\\HttpKernelInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getKernelMock()
    {
        return $this->getMock('Symfony\\Component\\HttpKernel\\KernelInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getTerminableMock()
    {
        return $this
            ->getMockBuilder('Symfony\\Component\\HttpKernel\\Kernel')
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRequestMock()
    {
        return $this
            ->getMockBuilder('Symfony\\Component\\HttpFoundation\\Request')
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getResponseMock()
    {
        return $this
            ->getMockBuilder('Symfony\\Component\\HttpFoundation\\Response')
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->getMock();
    }
}

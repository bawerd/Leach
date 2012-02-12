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

use Leach\Socket;
use Leach\Test\TestCase;

class SocketTest extends TestCase
{
    /**
     * @covers \Leach\Socket::__construct
     * @covers \Leach\Socket::__toString
     * @covers \Leach\Socket::getProtocol
     * @covers \Leach\Socket::getAddress
     * @covers \Leach\Socket::getPort
     */
    public function testSocket()
    {
        $socket = new Socket('tcp://127.0.0.1:9997');
        $this->assertEquals('tcp', $socket->getProtocol());
        $this->assertEquals('127.0.0.1', $socket->getAddress());
        $this->assertEquals(9997, $socket->getPort());

        $socket = new Socket($socket);
        $this->assertEquals('tcp', $socket->getProtocol());
        $this->assertEquals('127.0.0.1', $socket->getAddress());
        $this->assertEquals(9997, $socket->getPort());
    }

    /**
     * @covers \Leach\Socket::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidUrl()
    {
        $socket = new Socket('this\is_no+url');
    }

    /**
     * @covers \Leach\Socket::__construct
     * @covers \Leach\Socket::setProtocol
     */
    public function testProtocol()
    {
        $socket = new Socket('tcp://127.0.0.1:9997');
        $socket->setProtocol('tcp');
        $this->assertEquals('tcp', $socket->getProtocol());

        try {
            $socket->setProtocol('udp');
        } catch (\InvalidArgumentException $e) {
            return;
        }

        $this->fail('Expected exception \InvalidArgumentException');
    }

    /**
     * @covers \Leach\Socket::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidProtocol()
    {
        $socket = new Socket('udp://127.0.0.1:9997');
    }

    /**
     * @covers \Leach\Socket::__construct
     * @covers \Leach\Socket::setAddress
     */
    public function testAddress()
    {
        $socket = new Socket('tcp://127.0.0.1:9997');

        $socket->setAddress('192.168.0.1');
        $this->assertEquals('192.168.0.1', $socket->getAddress());

        $socket->setAddress('::1');
        $this->assertEquals('::1', $socket->getAddress());

        try {
            $socket->setAddress('invalid');
        } catch (\InvalidArgumentException $e) {
            return;
        }

        $this->fail('Expected exception \InvalidArgumentException');
    }

    /**
     * @covers \Leach\Socket::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidAddress()
    {
        $socket = new Socket('tcp://address:9997');
    }

    /**
     * @covers \Leach\Socket::__construct
     * @covers \Leach\Socket::setPort
     */
    public function testPort()
    {
        $socket = new Socket('tcp://127.0.0.1:9997');

        $socket->setPort(1234);
        $this->assertEquals(1234, $socket->getPort());

        try {
            $socket->setPort('integer');
        } catch (\InvalidArgumentException $e) {
            return;
        }

        $this->fail('Expected exception \InvalidArgumentException');
    }

    /**
     * @covers \Leach\Socket::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidPort()
    {
        $socket = new Socket('tcp://127.0.0.1:invalid');
    }

}

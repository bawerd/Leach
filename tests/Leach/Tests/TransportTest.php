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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Leach\Test\TestCase;
use Leach\Test\TestTransport;
use Leach\Transport;

class TransportTest extends TestCase
{
    /**
     * @link http://pear.zero.mq/
     */
    public function setUp()
    {
        if (!extension_loaded('zmq')) {
            $this->markTestSkipped('missing zmq extension');
        }
    }

    /**
     * @covers \Leach\Transport::__construct
     */
    public function testTransport()
    {
        $transport = new TestTransport('tcp://127.0.0.1:9998', 'test');

        $sendSpec = $transport->getSendSpec();
        $this->assertInstanceOf('Leach\\Socket', $sendSpec);
        $this->assertEquals('tcp', $sendSpec->getProtocol());
        $this->assertEquals('127.0.0.1', $sendSpec->getAddress());
        $this->assertEquals(9998, $sendSpec->getPort());
        $this->assertNull($transport->getSend());

        $this->assertEquals('test', $transport->getSendId());

        $recvSpec = $transport->getRecvSpec();
        $this->assertInstanceOf('Leach\\Socket', $recvSpec);
        $this->assertEquals('tcp', $recvSpec->getProtocol());
        $this->assertEquals('127.0.0.1', $recvSpec->getAddress());
        $this->assertEquals(9999, $recvSpec->getPort());

        // must not connect with object instantiation
        $this->assertNull($transport->getRecv());
        $this->assertNull($transport->getSend());
    }

    /**
     * @depends testTransport
     * @covers \Leach\Transport::connect
     */
    public function testConnect()
    {
        $transport = new TestTransport('tcp://127.0.0.1:9998', 'test');
        $transport->connect();

        $send = $transport->getSend();
        $this->assertInstanceOf('ZMQSocket', $send);
        $this->assertEquals('test', $send->getSockOpt(\ZMQ::SOCKOPT_IDENTITY));
        $this->assertInstanceOf('ZMQSocket', $transport->getRecv());
    }

    /**
     * @depends testConnect
     * @covers \Leach\Transport::disconnect
     */
    public function testDisconnect()
    {
        $transport = new TestTransport('tcp://127.0.0.1:9998', 'test');
        $transport->connect();
        $transport->disconnect();

        $this->assertNull($transport->getRecv());
        $this->assertNull($transport->getSend());
    }

    /**
     * @covers \Leach\Transport::recv
     * @expectedException \RuntimeException
     */
    public function testNotConnectedRecv()
    {
        $transport = new TestTransport('tcp://127.0.0.1:9998', 'test');
        $transport->recv();
    }

    /**
     * @covers \Leach\Transport::recv
     * @expectedException \InvalidArgumentException
     */
    public function testEmptyMessageRecv()
    {
        $recv = $this->getZmqSocketMock();
        $recv
            ->expects($this->once())
            ->method('recv')
            ->will($this->returnValue(' '));

        $transport = new TestTransport('tcp://127.0.0.1:9998', 'test');
        $transport->setRecv($recv); // fake connect
        $transport->recv();
    }

    /**
     * @todo increase code coverage w/ a more complex tnetstring
     *
     * @covers \Leach\Transport::recv
     */
    public function testRecv()
    {
        $recv = $this->getZmqSocketMock();
        $recv
            ->expects($this->once())
            ->method('recv')
            ->will($this->returnValue($this->getRequestMessage()));

        $transport = new TestTransport('tcp://127.0.0.1:9998', 'test');
        $transport->setRecv($recv); // fake connect

        $request = $transport->recv();

        $this->assertInstanceOf('Symfony\\Component\\HttpFoundation\\Request', $request);

        $this->assertEquals('/', $request->getRequestUri());

        $this->assertTrue($request->headers->has('Host'));
        $this->assertEquals('localhost', $request->headers->get('Host'));

        $this->assertTrue($request->headers->has('X-Leach-Id'));
        $this->assertEquals('test', $request->headers->get('X-Leach-Id'));

        $this->assertTrue($request->headers->has('X-Leach-Listener'));
        $this->assertEquals(1, $request->headers->get('X-Leach-Listener'));
    }

    /**
     * @covers \Leach\Transport::send
     * @expectedException \RuntimeException
     */
    public function testNotConnectedSend()
    {
        $transport = new TestTransport('tcp://127.0.0.1:9998', 'test');
        $transport->send($this->getRequestMock(), $this->getResponseMock());
    }

    /**
     * @todo
     * @covers \Leach\Transport::send
     */
    public function testSend()
    {
        $send = $this->getZmqSocketMock();
        $send
            ->expects($this->once())
            ->method('send')
            ->with($this->equalTo($this->getResponseMessage()));

        $request = new Request();
        $request->headers->set('X-Leach-Id', 'test');
        $request->headers->set('X-Leach-Listener', 1);

        $response = new Response();

        $transport = new TestTransport('tcp://127.0.0.1:9998', 'test');
        $transport->setSend($send); // fake connect
        $transport->send($request, $response);
    }

    /**
     * @return string
     */
    private function getRequestMessage()
    {
        return 'test 1 / 20:{"Host":"localhost"},0:,';
    }

    /**
     * @return string
     */
    private function getResponseMessage()
    {
        // @see \Symfony\Component\HttpFoundation\Response::setDate()
        $date = new \DateTime(null, new \DateTimeZone('UTC'));
        $date = $date->format('D, d M Y H:i:s').' GMT';

        return "test 1:1, HTTP/1.0 200 OK\r\n" .
               "Cache-Control:  no-cache\r\n" .
               "Content-Length: 0\r\n" .
               "Content-Type:   text/html; charset=UTF-8\r\n" .
               "Date:           $date\r\n" .
               "\r\n\r\n";
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getZmqSocketMock()
    {
        return $this
            ->getMockBuilder('ZMQSocket')
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->getMock();
    }
}

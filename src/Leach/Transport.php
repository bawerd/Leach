<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leach;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Transport
{
    /**
     * @var Socket
     */
    protected $sendSpec;

    /**
     * @var string
     */
    protected $sendId;

    /**
     * @var Socket
     */
    protected $recvSpec;

    /**
     * @var \ZMQContext
     */
    protected $context;

    /**
     * @var \ZMQSocket
     */
    protected $recv;

    /**
     * @var \ZMQSocket
     */
    protected $send;

    /**
     * Constructor.
     *
     * @param mixed $sendSpec
     * @param string $sendId
     * @param mixed $recvSpec (optional)
     * @param string $recvId (optional)
     *
     * @throws \RuntimeException
     */
    public function __construct($sendSpec, $sendId, $recvSpec = null)
    {
        if (!class_exists('ZMQ')) {
            // @codeCoverageIgnoreStart
            throw new \RuntimeException('zmq extension');
            // @codeCoverageIgnoreEnd
        }

        $this->sendSpec = new Socket($sendSpec);
        $this->sendId = $sendId;

        // assume port increased by one
        if (null === $recvSpec) {
            $recvSpec = clone $this->sendSpec;
            $recvSpec->setPort($recvSpec->getPort() + 1);
        }

        $this->recvSpec = new Socket($recvSpec);
    }

    /**
     * Connects to remote endpoints.
     *
     * @return void
     *
     * @link http://mongrel2.org/static/book-finalch6.html#x8-720005.3.1
     */
    public function connect()
    {
        if (null === $this->context) {
            $this->context = new \ZMQContext();
        }

        // receiving socket (sending socket from Mongrel2)
        $this->recv = $this->context->getSocket(\ZMQ::SOCKET_PULL);
        $this->recv->connect($this->recvSpec);

        // sending socket (receiving socket from Mongrel2)
        $this->send = $this->context->getSocket(\ZMQ::SOCKET_PUB);
        $this->send->setSockOpt(\ZMQ::SOCKOPT_IDENTITY, $this->sendId);
        $this->send->connect($this->sendSpec);
    }

    /**
     * Disconnects from remote endpoints.
     *
     * @return void
     */
    public function disconnect()
    {
        $this->recv = null;
        $this->send = null;
    }

    /**
     * Receives a request.
     *
     * @todo improve Request creation
     *
     * @return Request
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     *
     * @see Request::create
     * @see Request::createFromGlobals
     */
    public function recv()
    {
        if (null === $this->recv) {
            throw new \RuntimeException('not connected');
        }

        $message = $this->recv->recv();
        if (!trim($message)) {
            throw new \InvalidArgumentException('invalid message');
        }

        list($uuid, $listener, $path, $remaining) = explode(' ', $message, 4);
        list($headers, $body) = $this->decode($remaining);
        $headers = json_decode($headers, true);

        // split headers (uppercase = server variables)
        $server = array();
        foreach ($headers as $key => $value) {
            if (ctype_upper($key)) {
                $server[strtolower($key)] = $value;
                unset($headers[$key]);
            }
        }

        // request method
        $method = 'GET';
        if (array_key_exists('method', $server)) {
            $method = strtoupper($server['method']);
        }

        // query string
        $query = array();
        if (array_key_exists('query', $server)) {
            parse_str($server['query'], $query);
        }

        $request = Request::create(
            $path,
            $method,
            array(),
            array(),
            array(),
            $server,
            $body
        );

        // replace headers completely
        $request->headers->replace($headers);

        // replace get parameters
        $request->query->replace($query);

        // parse content as request parameters
        if (0 === strpos($request->headers->get('Content-Type'), 'application/x-www-form-urlencoded')
            && in_array($method, array('PUT', 'DELETE', 'PATCH'))
        ) {
            parse_str($request->getContent(), $data);
            $request->request = new ParameterBag($data);
        }

        // store some additional headers
        $request->headers->set('X-Leach-Id', $uuid);
        $request->headers->set('X-Leach-Listener', $listener);

        return $request;
    }

    /**
     * Sends a response for a request.
     *
     * @todo streamed responses
     * @todo cookie headers
     *
     * @param Request $request A Request instance
     * @param Response $response A Response instance
     *
     * @return void
     *
     * @throws \RuntimeException
     *
     * @see Response::prepare
     */
    public function send(Request $request, Response $response)
    {
        if (null === $this->send) {
            throw new \RuntimeException('not connected');
        }

        $response->prepare($request);

        $uuid = $request->headers->get('X-Leach-Id');

        $listeners = $request->headers->get('X-Leach-Listener', null, false);
        $listeners = $this->encode(implode(' ', $listeners));

        $statusCode = $response->getStatusCode();
        $reasonPhrase = Response::$statusTexts[$statusCode];

        $httpVersion = $response->getProtocolVersion();

        ob_start();
        $response->sendContent();
        $content = ob_get_contents();
        ob_clean();

        if (!$response->headers->has('Content-Length')) {
            $response->headers->set('Content-Length', strlen($content));
        }

        $message = sprintf("%s %s HTTP/%s %d %s\r\n%s\r\n%s\r\n",
            $uuid,
            $listeners,
            $httpVersion,
            $statusCode,
            $reasonPhrase,
            $response->headers,
            $content
        );

        $this->send->send($message);
    }

    /**
     * Decodes a tnetstring encoded string.
     *
     * Feel free to re-implemend this method with your own tnetstring decoder.
     *
     * @param string $string A tnetstring encoded string to decode
     *
     * @return string A decoded tnetstring
     *
     * @see \TNetstring_Encoder::decode
     *
     * @codeCoverageIgnore
     */
    protected function decode($string)
    {
        static $decoder;

        if (null === $decoder) {
            $decoder = new \TNetstring_Decoder();
        }

        return $decoder->decode($string);
    }

    /**
     * Encodes a string as a tnetstring.
     *
     * Feel free to re-implemend this method with your own tnetstring encoder.
     *
     * @param string $string A string to encode as a tnetstring
     *
     * @return string A tnetstring encoded string
     *
     * @see \TNetstring_Encoder::encode
     *
     * @codeCoverageIgnore
     */
    protected function encode($string)
    {
        static $encoder;

        if (null === $encoder) {
            $encoder = new \TNetstring_Encoder();
        }

        return $encoder->encode($string);
    }
}

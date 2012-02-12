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

class Socket
{
    /**
     * @var string
     */
    private $protocol;

    /**
     * @var string
     */
    private $address;

    /**
     * @var integer
     */
    private $port;

    /**
     * @var array
     */
    static protected $protocols = array('tcp');

    /**
     * Constructor.
     *
     * @param mixed $url A 0MQ socket dsn
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($url)
    {
        if (!is_string($url)) {
            $url = (string) $url;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('invalid url');
        }

        $url = parse_url($url);

        $this->protocol = 'tcp';
        if (array_key_exists('scheme', $url)) {
            $this->setProtocol($url['scheme']);
        }

        if (!array_key_exists('host', $url)) {
            // @codeCoverageIgnoreStart
            throw new \InvalidArgumentException('missing host');
            // @codeCoverageIgnoreEnd
        }
        $this->setAddress($url['host']);

        if (!array_key_exists('port', $url)) {
            // @codeCoverageIgnoreStart
            throw new \InvalidArgumentException('missing port');
            // @codeCoverageIgnoreEnd
        }
        $this->setPort($url['port']);
    }

    /**
     * Returns a 0MQ socket dsn.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s://%s:%d',
            $this->protocol,
            $this->address,
            $this->port
        );
    }

    /**
     * Returns the protocol.
     *
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Sets the protocol.
     *
     * @param string $protocol A protocol
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function setProtocol($protocol)
    {
        if (!in_array($protocol, static::$protocols)) {
            throw new \InvalidArgumentException('invalid protocol');
        }

        $this->protocol = $protocol;
    }

    /**
     * Returns the address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address.
     *
     * @param string $address An address
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function setAddress($address)
    {
        if (false === filter_var($address, FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException('invalid address');
        }

        $this->address = $address;
    }

    /**
     * Returns the port number.
     *
     * @return integer
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set the port number.
     *
     * @param integer $port A port number
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function setPort($port)
    {
        if (false === filter_var($port, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException('invalid port');
        }

        $this->port = $port;
    }
}

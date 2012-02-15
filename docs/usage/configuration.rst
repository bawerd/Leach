===============
 Configuration
===============

---------
 Command
---------

start
=====

.. code-block:: php

    php leach.phar start <container> [options]

send-spec
---------

* **Type**: ``string``
* **Default**: ``tcp://127.0.0.1:9998``
* **Note**: equals Mongrel2 ``recv_spec``

**Example**

.. code-block:: php

    $container = new Container(array(
        'send_spec' => 'tcp://127.0.0.1:9998',
    ));

.. code-block:: console

    php leach.phar start <container> --send-spec=tcp://127.0.0.1:9998

send-id
-------

* **Type**: ``string``
* **Default**: ``296fef89-153f-4464-8f53-952b3a750b1b``

**Example**

.. code-block:: console

    php leach.phar start <container> --send-id=296fef89-153f-4464-8f53-952b3a750b1b


recv-spec
---------

* **Type**: ``string``
* **Default**: ``tcp://127.0.0.1:9999``
* **Note**: equals Mongrel2 ``send_spec``

**Example**

.. code-block:: console

    php leach.phar start <container> --recv-spec=tcp://127.0.0.1:9999

-----------
 Container
-----------

Server
======

expose_leach
------------

Adds a ``X-Leach-Version`` header to each ``Response``.

* **Type**: ``Boolean``
* **Default**: ``false``

**Example**

.. code-block:: php

    return new Container(array(
        'expose_leach' => false,
    ));

max_requests
------------

* **Type**: ``integer``
* **Default**: ``500``

**Example**

.. code-block:: php

    return new Container(array(
        'max_requests' => 500,
    ));

Transport
=========

send_spec
---------

* **Type**: ``string``
* **Default**: ``null``
* **Note**: equals Mongrel2 ``recv_spec``

**Example**

.. code-block:: php

    return new Container(array(
        'send_spec' => 'tcp://127.0.0.1:9998',
    ));

send_id
-------

* **Type**: ``string``
* **Default**: ``null``

**Example**

.. code-block:: php

    return new Container(array(
        'send_id' => '296fef89-153f-4464-8f53-952b3a750b1b',
    ));

recv_spec
---------

* **Type**: ``string``
* **Default**: ``null``
* **Note**: equals Mongrel2 ``send_spec``

**Example**

.. code-block:: php

    return new Container(array(
        'recv_spec' => 'tcp://127.0.0.1:9999',
    ));

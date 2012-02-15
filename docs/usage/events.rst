========
 Events
========

Each event has a getter method to access the payload. To access a ``$container`` payload, simply use a camelCased method like ``getContainer()``.

-------------
 leach.setup
-------------

Setup a container. The Symfony container uses ``leach.setup`` events to ``boot`` kernels.

* **Event:** ``Leach\Event\SetUpEvent``
* **Payload**
 * ``$container``: ``Leach\Container\ContainerInterface``

---------------
 leach.request
---------------

Filter a request.

* **Event:** ``Leach\Event\FilterRequestEvent``
* **Payload**
 * ``$container``: ``Leach\Container\ContainerInterface``
 * ``$request``: ``Symfony\Component\HttpFoundation\Request``

----------------
 leach.response
----------------

Filter a response. The server uses ``leach.response`` events to add a ``X-Leach-Version`` header to each response.

* **Event:** ``Leach\Event\FilterResponseEvent``
* **Payload**
 * ``$container``: ``Leach\Container\ContainerInterface``
 * ``$request``: ``Symfony\Component\HttpFoundation\Request``
 * ``$response``: ``Symfony\Component\HttpFoundation\Response``

----------------
 leach.teardown
----------------

Tear down a container. The Symfony container uses ``leach.teardown`` events to ``terminate`` and ``shutdown`` kernels.

* **Event:** ``Leach\Event\TearDownEvent``
* **Payload**
 * ``$container``: ``Leach\Container\ContainerInterface``
 * ``$request``: ``Symfony\Component\HttpFoundation\Request``
 * ``$response``: ``Symfony\Component\HttpFoundation\Response``

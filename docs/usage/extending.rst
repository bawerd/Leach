===========
 Extending
===========

Extending Leach is easy via intelligent usage of event_ listeners. You can
interact with Leach if you register your own listeners and provide an
``EventDispatcherInterface`` instance with your ``ContainerInterface``
instance. It is also possible to provide and make use of your own options_.

-----------------
 Filter response
-----------------

.. code-block:: php

    <?php

    require_once __DIR__.'/silex.phar';

    use Silex\Application;
    use Symfony\Component\EventDispatcher\EventDispatcher;
    use Leach\Container\SilexContainer;
    use Leach\Events;
    use Leach\Event\FilterResponseEvent;

    $app = new Application();

    $app->get('/hello/{name}', function($name) use($app) {
        return 'Hello '.$app->escape($name);
    });

    $dispatcher = new EventDispatcher();
    $dispatcher->addListener(Events::RESPONSE, function(FilterResponseEvent $event) {
        $event->getResponse()->headers->set('X-MyApp-Version', '1.2.3');
    });

    return new SilexContainer($app, array(), $dispatcher);

.. _event: events.html
.. _options: configuration.html

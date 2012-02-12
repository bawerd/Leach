Examples
========

Silex
-----

Download Silex Phar file:

.. code-block:: console

    wget http://silex.sensiolabs.org/get/silex.phar examples/silex/silex.phar

Configure and start Mongrel2:

.. code-block:: console

    cd examples
    m2sh load -config leach.conf -db leach.db
    m2sh start -host localhost -db leach.db

Example Silex container (as in ``examples/silex/container.php``):

.. code-block:: php

    <?php

    require_once __DIR__.'/silex.phar';

    use Silex\Application;
    use Leach\Container\SilexContainer;

    $app = new Application();

    $app->get('/hello/{name}', function($name) use($app) {
        return 'Hello '.$app->escape($name);
    });

    return new SilexContainer($app);


Start Leach:

.. code-block:: console

    php leach.phar start examples/silex/container.php --send-id=1e44c719-9d26-4992-8dd8-00142f650ea7

Symfony
-------

Install "Symfony Standard Edition" distribution:

.. code-block:: console

    git clone git://github.com/symfony/symfony-standard.git examples/symfony/symfony-standard
    cd examples/symfony/symfony-standard
    php bin/vendors install

Configure and start Mongrel2:

.. code-block:: console

    cd examples
    m2sh load -config leach.conf -db leach.db
    m2sh start -host localhost -db leach.db

Example Symfony container (as in ``examples/symfony/container.php``):

.. code-block:: php

    <?php

    require_once __DIR__.'/symfony-standard/app/bootstrap.php.cache';
    require_once __DIR__.'/symfony-standard/app/AppKernel.php';
    // require_once __DIR__.'/symfony-standard/app/AppCache.php';

    use Leach\Container\SymfonyContainer;

    $kernel = new AppKernel('prod', false);
    // $kernel = new AppCache($kernel);
    $kernel->loadClassCache();

    return new SymfonyContainer($kernel);


Start Leach:

.. code-block:: console

    php leach.phar start examples/symfony/container.php --send-id=0aa1d405-e5b5-4a0c-a222-3fc4e30e0e6d

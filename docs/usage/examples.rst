==========
 Examples
==========

----------
 Mongrel2
----------

Configure and start Mongrel2:

.. code-block:: console

    cd examples && mkdir -p logs run
    m2sh load -config leach.conf -db leach.db
    m2sh start -host localhost -db leach.db

Example Mongrel2 configuration (as in ``examples/leach.conf``):

.. code-block:: lua

    leach = Handler(send_spec = 'tcp://127.0.0.1:9997',
                    send_ident = 'b6c95667-4ede-4cf0-b2de-a54d826576c9',
                    recv_spec = 'tcp://127.0.0.1:9996',
                    recv_ident = '')

    localhost = Host(name="localhost", routes={
        '/': leach
    })

    main = Server(
        uuid = "2dfc4c3b-1a6d-4965-a924-66ff081c3c29",
        access_log = "/logs/access.log",
        error_log = "/logs/error.log",
        chroot = "./",
        default_host = "localhost",
        name = "leach",
        pid_file = "/run/mongrel2.pid",
        port = 3000,
        hosts = [localhost]
    )

    servers = [main]

-------
 Silex
-------

Download Silex Phar file:

.. code-block:: console

    wget http://silex.sensiolabs.org/get/silex.phar examples/silex/silex.phar

Start Leach:

.. code-block:: console

    php leach.phar start examples/silex/container.php --send-id=1e44c719-9d26-4992-8dd8-00142f650ea7

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

---------
 Symfony
---------

Install "Symfony Standard Edition" distribution:

.. code-block:: console

    git clone git://github.com/symfony/symfony-standard.git examples/symfony/symfony-standard
    cd examples/symfony/symfony-standard
    php bin/vendors install

Start Leach:

.. code-block:: console

    php leach.phar start examples/symfony/container.php --send-id=0aa1d405-e5b5-4a0c-a222-3fc4e30e0e6d

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

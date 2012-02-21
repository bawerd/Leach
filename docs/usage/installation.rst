==============
 Installation
==============

---------------------------
 Phar file *(recommended)*
---------------------------

Download Leach Phar file:

.. code-block:: console

    wget http://leach.io/leach.phar
    php leach.phar


----------
 Composer
----------

Add the following entry to your ``composer.json``:

.. code-block:: json

    { "require": { "leach/leach": "dev-master" }}

Checkout `detailed package information on Packagist`_.

------------------------------
 PEAR package *(coming soon)*
------------------------------

Coming soon.

-------------------
 Clone from GitHub
-------------------

Clone Leach git repository:

.. code-block:: console

    git clone git://github.com/pminnieur/Leach.git leach

Download Composer Phar file and install dependencies:

.. code-block:: console

    wget http://getcomposer.org/composer.phar
    php composer.phar install --install-suggests

Run ``leach`` executable from ``bin`` directory:

.. code-block:: console

    php bin/leach


.. _detailed package information on Packagist:
    http://packagist.org/packages/leach/leach

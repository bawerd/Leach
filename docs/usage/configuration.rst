===============
 Configuration
===============

---------
 Command
---------

start
=====

send-spec
---------

* **Type**: string
* **Default**: tcp://127.0.0.1:9998
* **Note**: equals Mongrel2 *recv_spec*

send-id
-------

* **Type**: string
* **Default**: 296fef89-153f-4464-8f53-952b3a750b1b

recv-spec
---------

* **Type**: string
* **Default**: tcp://127.0.0.1:9999
* **Note**: equals Mongrel2 *send_spec*

-----------
 Container
-----------

Server
======

max_requests
------------

* **Type**: integer
* **Default**: 500

Transport
=========

send_spec
---------

* **Type**: string
* **Default**: null
* **Note**: equals Mongrel2 *recv_spec*

send_id
-------

* **Type**: string
* **Default**: null

recv_spec
---------

* **Type**: string
* **Default**: null
* **Note**: equals Mongrel2 *send_spec*

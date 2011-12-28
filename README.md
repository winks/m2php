m2php - a library to develop Mongrel2 handlers in PHP
=====================================================

* Mongrel2: <http://mongrel2.org>
* Mongrel2 Python Library: <http://sheddingbikes.com/posts/1279007133.html>

Requirements
------------

* ZeroMQ 2.0.7 or later: <http://www.zeromq.org/>
* PHP 5.3: <http://php.net>
* ZeroMQ PHP bindings: <http://www.zeromq.org/bindings:php>

Fetch
-----

The recommended way to install m2php is [through composer](http://packagist.org).

Just create a composer.json file for your project:

    {
        "require": {
            "mongrel2/mongrel2": "*"
        }
    }

And run these two commands to install it:

    $ wget http://getcomposer.org/composer.phar
    $ php composer.phar install

Now you can add the autoloader, and you will have access to the library:

```php
<?php
require 'vendor/.composer/autoload.php';
```

Usage
-----

```php
<?php

use Mongrel2\Connection;

$sender_id = "82209006-86FF-4982-B5EA-D1E29E55D481";
$conn = new Connection($sender_id, "tcp://127.0.0.1:9997", "tcp://127.0.0.1:9996");

while (true) {
    $req = $conn->recv();

    if ($req->is_disconnect()) {
        continue;
    }

    $this->conn->reply_http($req, 'Hello World');
}
```

Example
-------

To run the example, run the following commands:

    $ cd example
    $ m2sh load
    $ m2sh start
    # in a separate shell
    $ php hello.php
    # in a separate shell or browser
    $ curl http://localhost:6767/hello

Tests
-----

Before running the tests you need to have composer set up an autoloader:

    $ wget http://getcomposer.org/composer.phar
    $ php composer.phar install

Now you can run the unit tests.

    $ phpunit

LICENSE
-------
New BSD, see LICENSE.

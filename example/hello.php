<?php

use Mongrel2\Connection;

require __DIR__.'/../vendor/autoload.php';

$sender_id = "82209006-86FF-4982-B5EA-D1E29E55D481";
$conn = new Connection($sender_id, "tcp://127.0.0.1:9997", "tcp://127.0.0.1:9996");

while (true) {
    $req = $conn->recv();

    if ($req->is_disconnect()) {
        continue;
    }

    $conn->reply_http($req, 'Hello World');
}

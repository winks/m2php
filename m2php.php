<?php

use Mongrel2\Connection;

$sender_id = "82209006-86FF-4982-B5EA-D1E29E55D481";
$conn = new Connection($sender_id, "tcp://127.0.0.1:9997", "tcp://127.0.0.1:9996");

class MyHandler
{
    public function handle(Request $req)
    {
        $this->conn->reply_http($req, 'Hello World');
    }
}

$handler = new MyHandler($conn);

$runner = new Runner($conn, $handler);
$runner->run();

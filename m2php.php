<?php
namespace m2php;

require 'm2conn.php';
require 'm2req.php';
require 'm2tools.php';

$sender_id = "82209006-86FF-4982-B5EA-D1E29E55D481";

$conn = new  \m2php\Connection($sender_id, "tcp://127.0.0.1:9989", "tcp://127.0.0.1:9988");

while (true) {
    echo "waiting";

    $req = $conn->recv();

    if ($req->is_disconnect()) {
        echo "DISC";
        continue;
    }

    $pre = <<<EOD
<pre>
SENDER: %s
IDENT:%s
PATH: %s
HEADERS:%s
BODY:%s
</pre>
EOD;
    $response = sprintf($pre, $req->sender, $req->conn_id, $req->path, json_encode($req->headers), $req->body);

    echo $response;

    $conn->reply_http($req, $response);
}

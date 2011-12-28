<?php

namespace Mongrel2;

require 'm2conn.php';
require 'm2req.php';
require 'm2tools.php';

$sender_id = "82209006-86FF-4982-B5EA-D1E29E55D481";

$conn = new  \m2php\Connection($sender_id, "tcp://127.0.0.1:9997", "tcp://127.0.0.1:9996");

while (true) {
    echo "WAITING FOR REQUEST" . PHP_EOL;

    $req = $conn->recv();

    if ($req->is_disconnect()) {
        echo "DISCONNECT" . PHP_EOL;
        continue;
    }

    $pre = "<pre>\nSENDER: %s\nIDENT:%s\nPATH: %s\nHEADERS:%s\nBODY:%s</pre>\n";
    $response = sprintf($pre, $req->sender, $req->conn_id, $req->path, json_encode($req->headers), $req->body);

    echo $response;

    $conn->reply_http($req, $response);
}

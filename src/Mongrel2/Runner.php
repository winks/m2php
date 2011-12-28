<?php

namespace Mongrel2;

class Runner
{
    private $conn;
    private $handler;

    public function __construct(Connection $conn, HandlerInterface $handler)
    {
        $this->conn = $conn;
        $this->handler = $handler;
    }

    public function run()
    {
        while (true) {
            $req = $conn->recv();

            if ($req->is_disconnect()) {
                $handler->handleDisconnect($req);
                continue;
            }

            $this->handler->handle($req);
        }
    }
}

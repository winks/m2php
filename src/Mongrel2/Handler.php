<?php

namespace Mongrel2;

class Handler implements HandlerInterface
{
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function handleDisconnect(Request $request)
    {
    }
}

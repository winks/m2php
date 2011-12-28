<?php

namespace Mongrel2;

class Handler implements HandlerInterface
{
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    function handle(Request $request)
    {
    }

    function handleDisconnect(Request $request)
    {
    }
}

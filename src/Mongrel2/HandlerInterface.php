<?php

namespace Mongrel2;

interface HandlerInterface
{
    function handle(Request $request);
    function handleDisconnect(Request $request);
}

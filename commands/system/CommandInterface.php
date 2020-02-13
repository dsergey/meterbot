<?php

namespace MeterDataBot\Commands\System;

use Throwable;

interface CommandInterface
{
    /**
     * @param array $payload
     *
     * @return void
     *
     * @throws Throwable
     */
    public function execute(array $payload): void;
}
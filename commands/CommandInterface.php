<?php

namespace MeterDataBot\Commands;

interface CommandInterface
{
    /**
     * @param array $payload
     *
     * @return void
     */
    public function execute(array $payload): void;
}
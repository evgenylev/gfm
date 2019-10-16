<?php

namespace classes\interfaces;

interface AggregatorInterface
{
    /**
     * @param array $recipients
     * @param string $message
     * @return mixed
     */
    public function send($recipients, $message);
}
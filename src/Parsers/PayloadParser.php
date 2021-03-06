<?php

namespace Arkade\CommerceConnect\Parsers;

class PayloadParser
{
    /**
     * Parse the given JSON payload to an array.
     *
     * @param  string $payload
     * @return array|null
     */
    public function parse($payload)
    {
        return json_decode($payload, true);
    }
}
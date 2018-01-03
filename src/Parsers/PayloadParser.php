<?php

namespace Arkade\CommerceConnect\Parsers;

class PayloadParser
{
    /**
     * Parse the given XML payload to a SimpleXmlElement.
     *
     * @param  string $payload
     * @return array|null
     */
    public function parse($payload)
    {
        return json_decode($payload, true);
    }
}
<?php

namespace Arkade\CommerceConnect\Entities;

use Arkade\Support\Traits;
use Arkade\Support\Contracts;
use Illuminate\Support\Collection;

class Attribute {

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $base_type;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getBaseType()
    {
        return $this->base_type;
    }

    /**
     * @param string $base_type
     */
    public function setBaseType($base_type)
    {
        $this->base_type = $base_type;
    }

}
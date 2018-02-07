<?php

namespace Arkade\CommerceConnect\Entities;

use Illuminate\Support\Collection;

class PriceItem {

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $productId;

    /**
     * @var string
     */
    public $amount;

    /**
     * @var Carbon
     */
    public $from;

    /**
     * @var Carbon
     */
    public $to;

    /**
     * @var string
     */
    public $description;

    /**
     * @var boolean
     */
    public $enabled;

}
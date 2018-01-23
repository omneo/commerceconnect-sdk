<?php

namespace Arkade\CommerceConnect\Entities;

use Illuminate\Support\Collection;

class Product {

    /**
     * @var int
     */
    public $id;

    /**
     * @var int|null
     */
    public $parent_id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $product_type;

    /**
     * @var string
     */
    public $class_type;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $status;

    /**
     * @var Collection
     */
    public $attributes;

    /**
     * @var string
     */
    public $discontinued;

    /**
     * @var string
     */
    public $public_url;

    /**
     * @var string
     */
    public $image_url;

    /**
     * @var string
     */
    public $condition;

    /**
     * @var string
     */
    public $availability;

    /**
     * @var string
     */
    public $price;

    /**
     * @var string
     */
    public $sale_price;

    /**
     * @var string
     */
    public $sale_price_effective_at;

    /**
     * @var string
     */
    public $sale_price_effective_till;

    /**
     * @var string
     */
    public $gtin;

    /**
     * @var string
     */
    public $brand;

    /**
     * @var string
     */
    public $mpn;

    /**
     * @var string
     */
    public $item_group_id;

    /**
     * @var string
     */
    public $group;

    /**
     * @var string
     */
    public $age_group;

    /**
     * @var string
     */
    public $color;

    /**
     * @var string
     */
    public $size;

    /**
     * @var string
     */
    public $shipping_weight;

    /**
     * @var Collection
     */
    public $shipping;
}
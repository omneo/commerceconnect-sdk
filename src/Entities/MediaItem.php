<?php

namespace Arkade\CommerceConnect\Entities;

use Illuminate\Support\Collection;

class MediaItem {

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
    public $mediaItemType;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $sortOrder;

    /**
     * @var int
     */
    public $position;

    /**
     * @var boolean
     */
    public $isPrimary;

    /**
     * @var boolean
     */
    public $isUploaded;

    /**
     * @var array
     */
    public $tagList;

}
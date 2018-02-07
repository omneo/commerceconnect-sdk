<?php

namespace Arkade\CommerceConnect\Entities;

use Illuminate\Support\Collection;

class Category {

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $parentId;

    /**
     * @var int
     */
    public $categorySetId;

    /**
     * @var string
     */
    public $path;

    /**
     * @var object
     */
    public $categorySet;

}
<?php

namespace Arkade\CommerceConnect\Parsers;

use Arkade\CommerceConnect\Entities\Category;
use Illuminate\Support\Collection;

class CategoryParser
{
    /**
     * @var Category[]|Collection
     */
    protected $categories;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->categories = new Collection();
    }

    /**
     * Parse the given JSON payload to a SimpleXmlElement.
     *
     * @param  string $payload
     * @return Collection
     */
    public function parse($payload)
    {
        $data = (new PayloadParser)->parse($payload);

        /** @var array|null $categories */
        if ($categories = array_get($data, 'entries')) {
            foreach ($categories as $category) {
                $this->mapCategory($category);
            }
        }

        return $this->categories;
    }

    /**
     * @param $data
     */
    private function mapCategory($data)
    {
        $mapped = array_filter([
            'id'                        => array_get($data, 'id'),
            'name'                      => array_get($data, 'name'),
            'description'               => array_get($data, 'description'),
            'parentId'                  => array_get($data, 'parent_id'),
            'categorySetId'             => array_get($data, 'category_set_id'),
            'path'                      => array_get($data, 'path'),
            'categorySet'               => array_get($data, 'categorySet'),
        ]);

        if (\count($mapped)) {
            $category = new Category();
            foreach ($mapped as $key => $val) {
                $category->{$key} = $val;
            }
            $this->categories->push($category);
        }
    }

}

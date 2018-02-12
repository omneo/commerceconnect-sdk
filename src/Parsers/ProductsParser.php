<?php

namespace Arkade\CommerceConnect\Parsers;

use Arkade\CommerceConnect\Entities\Attribute;
use Arkade\CommerceConnect\Entities\Product;
use Illuminate\Support\Collection;

class ProductsParser
{
    /**
     * @var Product[]|Collection
     */
    protected $products;

    /**
     * ProductsParser constructor.
     */
    public function __construct()
    {
        $this->products = new Collection();
    }

    /**
     * Parse the given JSON payload to a SimpleXmlElement.
     *
     * @param string $payload
     * @param Action the caller action class
     * @return Collection
     */
    public function parse($payload, $action)
    {
        $data = (new PayloadParser)->parse($payload);

        $action->total_pages = $data['total_pages'];
        $action->total_entries = $data['total_entries'];

        /** @var array|null $products */
        if ($products = array_get($data, 'entries')) {
            foreach ($products as $product) {
                $this->mapProduct($product);
            }
        }

        return $this->products;
    }

    /**
     * @param $data
     * @param null $parent_id
     */
    private function mapProduct($data, $parent_id = null)
    {
        $mapped = array_filter([
            'id'                        => array_get($data, 'id'),
            'parent_id'                 => $parent_id,
            'title'                     => array_get($data, 'name'),
            'description'               => array_get($data, 'product_attributes.description.value'),
            'product_type'              => array_get($data, 'product_type'),
            'class_type'                => array_get($data, 'class_type'),
            'code'                      => array_get($data, 'code'),
            'status'                    => array_get($data, 'status'),
            'attributes'                => $this->mapAttributes($data),
            'discontinued'              => array_get($data, 'discontinued'),
            'public_url'                => array_get($data, 'public_url'),
            'image_url'                 => array_get($data, 'image_url'),
            'condition'                 => array_get($data, 'condition'),
            'availability'              => array_get($data, 'availability'),
            'price'                     => array_get($data, 'price'),
            'sale_price'                => array_get($data, 'sale_price'),
            'sale_price_effective_at'   => array_get($data, 'sale_price_effective_at'),
            'sale_price_effective_till' => array_get($data, 'sale_price_effective_till'),
            'gtin'                      => array_get($data, 'gtin'),
            'brand'                     => array_get($data, 'brand'),
            'mpn'                       => array_get($data, 'mpn'),
            'item_group_id'             => array_get($data, 'item_group_id'),
            'group'                     => array_get($data, 'group'),
            'age_group'                 => array_get($data, 'age_group'),
            'color'                     => array_get($data, 'color'),
            'size'                      => array_get($data, 'size'),
            'shipping_weight'           => array_get($data, 'shipping_weight'),
            'shipping'                  => array_get($data, 'shipping'),
        ]);

        if (\count($mapped)) {
            $product = new Product();
            foreach ($mapped as $key => $val) {
                $product->{$key} = $val;
            }
            $this->products->push($product);

            /** @var array|null $children */
            if ($children = array_get($data, 'children')) {
                foreach ($children as $product) {
                    $this->mapProduct($product, array_get($mapped, 'id'));
                }
            }
        }
    }

    /**
     * @param $data
     * @return Collection
     */
    private function mapAttributes($data)
    {
        $collection = new Collection();
        /** @var array|null $attributes */
        if ($attributes = array_get($data, 'product_attributes')) {
            foreach ($attributes as $key => $item) {
                $attribute            = new Attribute();
                $attribute->key       = $key;
                $attribute->value     = $item['value'];
                $attribute->base_type = $item['base_type'];

                $collection->push($attribute);
            }
        }
        return $collection;
    }
}

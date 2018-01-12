<?php

namespace Arkade\CommerceConnect\Actions;

use GuzzleHttp\Psr7\Response;
use Arkade\CommerceConnect\Entities;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;

class GetProductsTest extends TestCase
{
    private function getProducts() {
        return (new GetProducts)->response(
            new Response(200, [], file_get_contents(__DIR__.'/../Stubs/Products/get_products.json'))
        );
    }

    /**
     * @test
     */
    public function response_is_a_collection_of_products()
    {
        $collection = $this->getProducts();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(12, $collection->count());
        $this->assertInstanceOf(Entities\Product::class, $collection->first());
        $this->assertInstanceOf(Entities\Product::class, $collection->last());
    }

    /**
     * @test
     */
    public function children_link_to_parent_via_id()
    {
        $collection = $this->getProducts();
        /** @var Entities\Product $product */
        $product = $collection->get(2);

        $this->assertInstanceOf(Entities\Product::class, $product);
        $this->assertEmpty($collection->first()->parent_id);
        $this->assertNotEmpty($product->parent_id);
    }

    /**
     * Confirm children aren't added to prevent overhead in dependent applications
     *
     * @test
     */
    public function product_children_not_mapped_to_model()
    {
        $collection = $this->getProducts();
        /** @var Entities\Product $product */
        $product = $collection->first();

        $this->assertObjectNotHasAttribute('children', $product);
    }


    /**
     * Confirm children aren't added to prevent overhead in dependent applications
     *
     * @test
     */
    public function products_has_attributes_collection()
    {
        $collection = $this->getProducts();
        /** @var Entities\Product $product */
        $product = $collection->first();
        /** @var Entities\Attribute[]|Collection $attributes */
        $attributes = $product->attributes;
        /** @var Entities\Attribute $attribute */
        $attribute = $attributes->first();

        $this->assertInstanceOf(Collection::class, $attributes);
        $this->assertEquals(21, $attributes->count());
        $this->assertInstanceOf(Entities\Attribute::class, $attribute);
        $this->assertEquals('ap21_cuff', $attribute->key);
        $this->assertEquals(null, $attribute->value);
    }

}
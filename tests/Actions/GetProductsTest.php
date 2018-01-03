<?php

namespace Arkade\CommerceConnect\Actions;

use GuzzleHttp\Psr7\Response;
use Arkade\CommerceConnect\Entities;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;

class GetProductsTest extends TestCase
{
    /**
     * @test
     */
    public function response_is_a_collection_of_products()
    {
        $collection = (new GetProducts)->response(
            new Response(200, [], file_get_contents(__DIR__.'/../Stubs/Products/get_products_without_children.json'))
        );

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertInstanceOf(Entities\Product::class, $collection->first());
    }

    /**
     * @test
     */
    public function response_is_a_collection_of_products_which_child_products()
    {
        $collection = (new GetProducts)->response(
            new Response(200, [], file_get_contents(__DIR__.'/../Stubs/Products/get_products_without_children.json'))
        );

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals($collection->count(), 1);
        $this->assertInstanceOf(Entities\Product::class, $collection->first());
    }

}
<?php

namespace Arkade\CommerceConnect\Actions;

use Arkade\CommerceConnect\Parsers\ProductsParser;
use GuzzleHttp;
use Arkade\CommerceConnect\Contracts;
use Illuminate\Support\Collection;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetProducts extends BaseAction implements Contracts\Action
{
    /**
     * @var int
     */
    public $per_page;

    /**
     * @var int
     */
    public $page;

    /**
     * @var int
     */
    public $total_pages;

    /**
     * @var int
     */
    public $total_entries;

    /**
     * ISO_8601 format 2016-05-30T05:13:26Z
     *
     * @var string
     */
    public $created_time_from;

    /**
     * ISO_8601 format 2016-05-30T05:13:26Z
     *
     * @var string
     */
    public $created_time_to;

    /**
     * ISO_8601 format 2016-05-30T05:13:26Z
     *
     * @var string
     */
    public $updated_time_from;

    /**
     * ISO_8601 format 2016-05-30T05:13:26Z
     *
     * @var string
     */
    public $updated_time_to;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $name;

    /**
     * Must be one of: active, inactive.
     *
     * @var string
     */
    public $status;

    /**
     * @var int
     */
    public $response_product_fields;

    /**
     * @var int
     */
    public $response_associated_fields;

    /**
     * Build a PSR-7 request.
     *
     * @return RequestInterface
     */
    public function request()
    {
        $request = new GuzzleHttp\Psr7\Request('GET', '/api/v2/products');

        return $request->withUri($request->getUri()->withQuery(http_build_query(
            $this->queryParameters()
        )));
    }

    /**
     * Transform a PSR-7 response.
     *
     * @param  ResponseInterface $response
     * @return Collection
     */
    public function response(ResponseInterface $response)
    {
        return (new ProductsParser())->parse((string)$response->getBody(), $this);
    }

    /**
     * @return array
     */
    private function queryParameters()
    {
        return array_filter([
            'per_page'                   => $this->per_page,
            'page'                       => $this->page,
            'created_time_from'          => $this->created_time_from,
            'created_time_to'            => $this->created_time_to,
            'updated_time_from'          => $this->updated_time_from,
            'updated_time_to'            => $this->updated_time_to,
            'id'                         => $this->id,
            'code'                       => $this->code,
            'name'                       => $this->name,
            'status'                     => $this->status,
            'response_product_fields'    => $this->response_product_fields,
            'response_associated_fields' => $this->response_associated_fields,
        ]);
    }
}
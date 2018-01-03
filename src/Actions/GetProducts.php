<?php

namespace Arkade\CommerceConnect\Actions;

use Arkade\CommerceConnect\Parsers\PayloadParser;
use GuzzleHttp;
use Arkade\CommerceConnect\Contracts;
use Illuminate\Support\Collection;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Pagination\LengthAwarePaginator;

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
     * @param array $options
     * @return RequestInterface
     */
    public function request(array $options = [])
    {
        $request = new GuzzleHttp\Psr7\Request('GET', '/products');

        return $request->withUri($request->getUri()->withQuery(http_build_query(
            $this->mapParameters($options)
        )));
    }

    /**
     * Transform a PSR-7 response.
     *
     * @param  ResponseInterface $response
     * @return LengthAwarePaginator|Collection
     */
    public function response(ResponseInterface $response)
    {
        $data = (new PayloadParser)->parse((string)$response->getBody());

        $collection = new Collection;

        foreach ($data->ProductSimple as $product) {
            $collection->push((new Parsers\ProductSimpleParser)->parse($product));
        }

        return $collection;
    }

    private function mapParameters()
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
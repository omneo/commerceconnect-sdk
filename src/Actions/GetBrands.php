<?php

namespace Arkade\CommerceConnect\Actions;

use Arkade\CommerceConnect\Parsers\BrandsParser;
use GuzzleHttp;
use Arkade\CommerceConnect\Contracts;
use Illuminate\Support\Collection;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetBrands extends BaseAction implements Contracts\Action
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
     * Build a PSR-7 request.
     *
     * @return RequestInterface
     */
    public function request()
    {
        $request = new GuzzleHttp\Psr7\Request('GET', '/api/v2/brands');

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
        return (new BrandsParser())->parse((string)$response->getBody());
    }

    /**
     * @return array
     */
    private function queryParameters()
    {
        return array_filter([
            'per_page'                   => $this->per_page,
            'page'                       => $this->page,
        ]);
    }
}
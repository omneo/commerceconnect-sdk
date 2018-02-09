<?php

namespace Arkade\CommerceConnect\Actions;

use Arkade\CommerceConnect\Parsers\MediaItemsParser;
use GuzzleHttp;
use Arkade\CommerceConnect\Contracts;
use Illuminate\Support\Collection;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetMediaItems extends BaseAction implements Contracts\Action
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
     * Build a PSR-7 request.
     *
     * @return RequestInterface
     */
    public function request()
    {
        $request = new GuzzleHttp\Psr7\Request('GET', '/api/v2/media_items');

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
        return (new MediaItemsParser())->parse((string)$response->getBody(), $this);
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
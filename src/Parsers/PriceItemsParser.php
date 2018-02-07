<?php

namespace Arkade\CommerceConnect\Parsers;

use Arkade\CommerceConnect\Entities\PriceItem;
use Illuminate\Support\Collection;

class PriceItemsParser
{
    /**
     * @var PriceItem[]|Collection
     */
    protected $priceItems;

    /**
     * ProductsParser constructor.
     */
    public function __construct()
    {
        $this->priceItems = new Collection();
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

        /** @var array|null $priceItems */
        if ($priceItems = array_get($data, 'entries')) {
            foreach ($priceItems as $priceItem) {
                $this->mapPriceItem($priceItem);
            }
        }

        return $this->priceItems;
    }

    /**
     * @param $data
     */
    private function mapPriceItem($data)
    {
        $mapped = array_filter([
            'id'                        => array_get($data, 'id'),
            'productId'                 => array_get($data, 'product_id'),
            'amount'                    => array_get($data, 'amount'),
            'from'                      => array_get($data, 'from'),
            'to'                        => array_get($data, 'to'),
            'description'               => array_get($data, 'description'),
            'enabled'                   => array_get($data, 'enbled'),
        ]);

        if (\count($mapped)) {
            $priceItem = new PriceItem();
            foreach ($mapped as $key => $val) {
                $priceItem->{$key} = $val;
            }
            $this->priceItems->push($priceItem);
        }
    }

}

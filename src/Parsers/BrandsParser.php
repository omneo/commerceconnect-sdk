<?php

namespace Arkade\CommerceConnect\Parsers;

use Arkade\CommerceConnect\Entities\Brand;
use Illuminate\Support\Collection;

class BrandsParser
{
    /**
     * @var Brand[]|Collection
     */
    protected $brands;

    /**
     * ProductsParser constructor.
     */
    public function __construct()
    {
        $this->brands = new Collection();
    }

    /**
     * Parse the given JSON payload to a SimpleXmlElement.
     *
     * @param  string $payload
     * @param Action the caller action class
     * @return Collection
     */
    public function parse($payload, $action)
    {
        $data = (new PayloadParser)->parse($payload);

        $action->total_pages = $data['total_pages'];
        $action->total_entries = $data['total_entries'];

        /** @var array|null $brands */
        if ($brands = array_get($data, 'entries')) {
            foreach ($brands as $brand) {
                $this->mapBrand($brand);
            }
        }

        return $this->brands;
    }

    /**
     * @param $data
     */
    private function mapBrand($data)
    {
        $mapped = array_filter([
            'id'                        => array_get($data, 'id'),
            'name'                      => array_get($data, 'name'),
            'description'               => array_get($data, 'description'),
            'logoLink'                  => array_get($data, 'logo_link'),
        ]);

        if (\count($mapped)) {
            $brand = new Brand();
            foreach ($mapped as $key => $val) {
                $brand->{$key} = $val;
            }
            $this->brands->push($brand);
        }
    }

}

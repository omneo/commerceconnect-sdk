<?php

namespace Arkade\CommerceConnect\Parsers;

use Arkade\CommerceConnect\Entities\MediaItem;
use Illuminate\Support\Collection;

class MediaItemsParser
{
    /**
     * @var MediaItem[]|Collection
     */
    protected $mediaItems;

    /**
     * ProductsParser constructor.
     */
    public function __construct()
    {
        $this->mediaItems = new Collection();
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

        /** @var array|null $mediaItems */
        if ($mediaItems = array_get($data, 'entries')) {
            foreach ($mediaItems as $mediaItem) {
                $this->mapMediaItem($mediaItem);
            }
        }

        return $this->mediaItems;
    }

    /**
     * @param $data
     */
    private function mapMediaItem($data)
    {
        $mapped = array_filter([
            'id'                        => array_get($data, 'id'),
            'productId'                 => array_get($data, 'product_id'),
            'mediaItemType'             => array_get($data, 'media_item_type'),
            'url'                       => array_get($data, 'url'),
            'sortOrder'                 => array_get($data, 'sort_order'),
            'isPrimary'                 => array_get($data, 'is_primary'),
            'isUploaded'                => array_get($data, 'is_uploaded'),
            'tagList'                   => array_get($data, 'tag_list'),
        ]);

        if (\count($mapped)) {
            $mediaItem = new MediaItem();
            foreach ($mapped as $key => $val) {
                $mediaItem->{$key} = $val;
            }
            $this->mediaItems->push($mediaItem);
        }
    }

}

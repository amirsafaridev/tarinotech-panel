<?php

namespace Modules\Factor\app\Resources\Factor;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FactorCollection extends ResourceCollection
{
    private array $pagination;

    public function __construct($resource)
    {
        $this->pagination = [
            'total' => $resource->total(),
            'count' => $resource->count(),
            'per_page' => $resource->perPage(),
            'current_page' => $resource->currentPage(),
            'total_pages' => $resource->lastPage(),
        ];

        $resource = $resource->getCollection();

        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     */
    public function toArray($request): array
    {
        return [
            'data' => FactorResource::collection($this),
            'paginate' => $this->pagination,
        ];
    }
}

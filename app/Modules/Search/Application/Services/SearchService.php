<?php

namespace Search\Application\Services;

use Search\Contracts\SearchRepositoryInterface;
use Illuminate\Support\Collection;

class SearchService
{
    public function __construct(
        private SearchRepositoryInterface $searchRepository
    ) {}

    public function search(array $filters): Collection
    {
        return $this->searchRepository->getAvailableHotels($filters);
    }
}

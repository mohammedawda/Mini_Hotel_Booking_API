<?php

namespace Search\Contracts;

use Illuminate\Support\Collection;

interface SearchRepositoryInterface
{
    /**
     * Get available hotels and room types based on filters.
     */
    public function getAvailableHotels(array $filters): Collection;
}

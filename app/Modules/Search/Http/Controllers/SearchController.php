<?php

namespace Search\Http\Controllers;

use App\Http\Controllers\Controller;
use Search\Application\Services\SearchService;
use Search\Http\Requests\SearchRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    use ApiResponse;

    public function __construct(
        private SearchService $searchService
    ) {}

    /**
     * Search for available rooms.
     */
    public function index(SearchRequest $request): JsonResponse
    {
        $results = $this->searchService->search($request->validated());
        return $this->sendResponse($results, "Available rooms retrieved successfully");
    }
}

<?php

namespace Hotels\Http\Controllers;

use App\Http\Controllers\Controller;
use Hotels\Application\Services\HotelService;
use Hotels\Http\Requests\StoreHotelRequest;
use Hotels\Http\Requests\UpdateHotelRequest;
use Hotels\Http\Resources\HotelResource;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

class HotelsController extends Controller
{
    use ApiResponse;

    public function __construct(
        private HotelService $hotelService
    ) {}

    public function index(): JsonResponse
    {
        $hotels = $this->hotelService->getAllHotels(request())->paginate();
        return $this->sendResponse(HotelResource::collection($hotels)->response()->getData(true), "Hotels retrieved successfully");
    }

    public function show(int $id): JsonResponse
    {
        $hotel = $this->hotelService->getHotelById($id);
        if (!$hotel) {
            return $this->sendError("Hotel not found", 404);
        }
        return $this->sendResponse(new HotelResource($hotel), "Hotel retrieved successfully");
    }

    public function store(StoreHotelRequest $request): JsonResponse
    {
        $hotel = $this->hotelService->createHotel($request->validated());
        return $this->sendResponse(new HotelResource($hotel), "Hotel created successfully", 201);
    }

    public function update(UpdateHotelRequest $request, int $id): JsonResponse
    {
        $success = $this->hotelService->updateHotel($id, $request->validated());
        if (!$success) {
            return $this->sendError("Hotel not found", 404);
        }
        return $this->sendResponse(new HotelResource($this->hotelService->getHotelById($id)), "Hotel updated successfully");
    }

    public function destroy(int $id): JsonResponse
    {
        $success = $this->hotelService->deleteHotel($id);
        if (!$success) {
            return $this->sendError("Hotel not found", 404);
        }
        return $this->sendResponse(null, "Hotel deleted successfully");
    }
}
